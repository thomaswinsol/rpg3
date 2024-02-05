(function() {
    // stores loaded label info
    var label;
    var _printers = [];

    function createPrintersTableRow(table, name, value) {
        var row = document.createElement("tr");

        var cell1 = document.createElement("td");
        cell1.appendChild(document.createTextNode(name + ': '));

        var cell2 = document.createElement("td");
        cell2.appendChild(document.createTextNode(value));

        row.appendChild(cell1);
        row.appendChild(cell2);

        table.appendChild(row);
    }

    function populatePrinterDetail() {
        var printerDetail = document.getElementById("printerDetail");
        printerDetail.innerHTML = "";

        var myPrinter = _printers[document.getElementById("printersSelect").value];
        if (myPrinter === undefined)
            return;

        var table = document.createElement("table");
        createPrintersTableRow(table, 'PrinterType', myPrinter['printerType'])
        createPrintersTableRow(table, 'PrinterName', myPrinter['name'])
        createPrintersTableRow(table, 'ModelName', myPrinter['modelName'])
        createPrintersTableRow(table, 'IsLocal', myPrinter['isLocal'])
        createPrintersTableRow(table, 'IsConnected', myPrinter['isConnected'])
        createPrintersTableRow(table, 'IsTwinTurbo', myPrinter['isTwinTurbo'])

        dymo.label.framework.is550PrinterAsync(myPrinter.name).then(function (isRollStatusSupported) {
            //fetch one consumable information in the printer list.
            if (isRollStatusSupported) {
                createPrintersTableRow(table, 'IsRollStatusSupported', 'True')
                dymo.label.framework.getConsumableInfoIn550PrinterAsync(myPrinter.name).then(function (consumableInfo) {
                    createPrintersTableRow(table, 'SKU', consumableInfo['sku'])
                    createPrintersTableRow(table, 'Consumable Name', consumableInfo['name'])
                    createPrintersTableRow(table, 'Labels Remaining', consumableInfo['labelsRemaining'])
                    createPrintersTableRow(table, 'Roll Status', consumableInfo['rollStatus'])
                }).thenCatch(function (e) {
                    createPrintersTableRow(table, 'SKU', 'n/a')
                    createPrintersTableRow(table, 'Consumable Name', 'n/a')
                    createPrintersTableRow(table, 'Labels Remaining', 'n/a')
                    createPrintersTableRow(table, 'Roll Status', 'n/a')
                })
            } else {
                createPrintersTableRow(table, 'IsRollStatusSupported', 'False')
            }
        }).thenCatch(function (e) {
            createPrintersTableRow(table, 'IsRollStatusSupported', e.message)
        })

        printerDetail.appendChild(table);
    }

    // called when the document completly loaded
    function onload() {
        var labelFile = document.getElementById('labelFile');
        var addressTextArea = document.getElementById('addressTextArea');
        var printersSelect = document.getElementById('printersSelect');
        var printButton = document.getElementById('printButton');


        // initialize controls
        printButton.disabled = true;
        addressTextArea.disabled = true;

        getlabels(0);
        // Generates label preview and updates corresponend <img> element
        // Note: this does not work in IE 6 & 7 because they don't support data urls
        // if you want previews in IE 6 & 7 you have to do it on the server side
        function updatePreview() {
            if (!label)
                return;

            var pngData = label.render();
            var labelImage = document.getElementById('labelImage');
            labelImage.src = "data:image/png;base64," + pngData;
        }

        // loads all supported printers into a combo box 
        function loadPrintersAsync() {
            _printers = [];
            dymo.label.framework.getPrintersAsync().then(function (printers) {
                if (printers.length == 0) {
                    alert("No DYMO printers are installed. Install DYMO printers.");
                    return;
                }
                _printers = printers;
                printers.forEach(function (printer) {
                    let printerName = printer["name"];
                    let option = document.createElement("option");
                    option.value = printerName;
                    option.appendChild(document.createTextNode(printerName));
                    printersSelect.appendChild(option);
                });
                populatePrinterDetail();
            }).thenCatch(function (e) {
                alert("Load Printers failed: " + e);;
                return;
            });
        }

        // returns current address on the label 
        function getAddress() {
            if (!label || label.getAddressObjectCount() == 0)
                return "";

            return label.getAddressText(0);
        }

        // set current address on the label 
        function setAddress(address) {
            if (!label || label.getAddressObjectCount() == 0)
                return;

            return label.setAddressText(0, address);
        }

        function getlabels(index){
            label = dymo.label.framework.openLabelXml("");
            var res=label.isValidLabel();
            // if (labelFile.files && labelFile.files[0] && typeof labelFile.files[0].getAsText == "function") {  // Firefox
            // // open file by providing xml label definition
            // // in this example the definition is read from a local file
            // // in real world example it can come from the server, e.g. using XMLHttpRequest()
            // label = dymo.label.framework.openLabelXml(labelFile.files[0].getAsText("utf-8"));
            // }
            // else {
            // try load by opening file directly
            // do it only if we have a full path
            var fileName = labelFile.value;
            if ((fileName.indexOf('/') >= 0 || fileName.indexOf('\\') >= 0) &&(fileName.indexOf('fakepath') <0 )) {
                label = dymo.label.framework.openLabelFile(fileName);
                if(label.isDCDLabel())
                    console.log("DYMO Connect label");
                if(label.isDLSLabel())
                    console.log("DLS label");
                if(label.isValidLabel())
                    console.log("The file is a valid label");
                else {
                    alert(" The file is not a valid label");
                    return;
                }
            }
            else {
                // the browser returned a file name only (without path). This heppens on Safari for example
                // in this case it is impossible to obtain file content using client-size only code,some server support is needed (see GMail IFrame file upload, ofr example)
                // so for this sample we will inform user and open a default address label
                //alert('The browser does not return full file path information. The sample will use a default label file');
                var element = document.getElementById('list');
                var children = element.children;
                var lblArt='';
                var lblOms='';
                var lblGebruik='';
                var lblLocatie='';
                str=children[index].textContent;
                const words = str.split('$');
                lblArt=words[0];
                lblOms=words[1];
                lblGebruik=words[2]+" "+words[3];
                lblLocatie=words[4];
                var testAddressLabelXml = '<?xml version="1.0" encoding="utf-8"?>\
<DieCutLabel Version="8.0" Units="twips">\
	<PaperOrientation>Landscape</PaperOrientation>\
	<Id>LargeAddress</Id>\
	<IsOutlined>false</IsOutlined>\
	<PaperName>30321 Large Address</PaperName>\
	<DrawCommands>\
		<RoundRectangle X="0" Y="0" Width="2025" Height="5020" Rx="270" Ry="270" />\
	</DrawCommands>\
	<ObjectInfo>\
		<AddressObject>\
			<Name>Adres</Name>\
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
			<LinkedObjectName />\
			<Rotation>Rotation0</Rotation>\
			<IsMirrored>False</IsMirrored>\
			<IsVariable>True</IsVariable>\
			<GroupID>-1</GroupID>\
			<IsOutlined>False</IsOutlined>\
			<HorizontalAlignment>Center</HorizontalAlignment>\
			<VerticalAlignment>Middle</VerticalAlignment>\
			<TextFitMode>ShrinkToFit</TextFitMode>\
			<UseFullFontHeight>True</UseFullFontHeight>\
			<Verticalized>False</Verticalized>\
			<StyledText>\
				<Element>\
					<String xml:space="preserve">'+lblOms+'</String>\
					<Attributes>\
						<Font Family="Arial" Size="10" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" HueScale="100" />\
					</Attributes>\
				</Element>\
			</StyledText>\
			<ShowBarcodeFor9DigitZipOnly>True</ShowBarcodeFor9DigitZipOnly>\
			<BarcodePosition>Suppress</BarcodePosition>\
			<LineFonts>\
				<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
			</LineFonts>\
		</AddressObject>\
		<Bounds X="322" Y="1006" Width="4613" Height="933.999999999999" />\
	</ObjectInfo>\
	<ObjectInfo>\
		<BarcodeObject>\
			<Name>STREEPJESCODE</Name>\
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
			<LinkedObjectName />\
			<Rotation>Rotation0</Rotation>\
			<IsMirrored>False</IsMirrored>\
			<IsVariable>False</IsVariable>\
			<GroupID>-1</GroupID>\
			<IsOutlined>False</IsOutlined>\
			<Text>'+lblArt+'</Text>\
			<Type>QRCode</Type>\
			<Size>Large</Size>\
			<TextPosition>None</TextPosition>\
			<TextFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
			<CheckSumFont Family="Arial" Size="8" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
			<TextEmbedding>None</TextEmbedding>\
			<ECLevel>0</ECLevel>\
			<HorizontalAlignment>Left</HorizontalAlignment>\
			<QuietZonesPadding Left="0" Top="0" Right="0" Bottom="0" />\
		</BarcodeObject>\
		<Bounds X="322" Y="360" Width="672" Height="720" />\
	</ObjectInfo>\
	<ObjectInfo>\
		<TextObject>\
			<Name>TEKST</Name>\
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
			<LinkedObjectName />\
			<Rotation>Rotation0</Rotation>\
			<IsMirrored>False</IsMirrored>\
			<IsVariable>False</IsVariable>\
			<GroupID>-1</GroupID>\
			<IsOutlined>False</IsOutlined>\
			<HorizontalAlignment>Center</HorizontalAlignment>\
			<VerticalAlignment>Middle</VerticalAlignment>\
			<TextFitMode>ShrinkToFit</TextFitMode>\
			<UseFullFontHeight>True</UseFullFontHeight>\
			<Verticalized>False</Verticalized>\
			<StyledText>\
				<Element>\
					<String xml:space="preserve">'+lblArt+'</String>\
					<Attributes>\
						<Font Family="Arial" Size="18" Bold="True" Italic="False" Underline="False" Strikeout="False" />\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" HueScale="100" />\
					</Attributes>\
				</Element>\
			</StyledText>\
		</TextObject>\
		<Bounds X="1131" Y="389.999999999998" Width="1716" Height="516" />\
	</ObjectInfo>\
	<ObjectInfo>\
		<TextObject>\
			<Name>TEKST_1</Name>\
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
			<LinkedObjectName />\
			<Rotation>Rotation0</Rotation>\
			<IsMirrored>False</IsMirrored>\
			<IsVariable>False</IsVariable>\
			<GroupID>-1</GroupID>\
			<IsOutlined>False</IsOutlined>\
			<HorizontalAlignment>Left</HorizontalAlignment>\
			<VerticalAlignment>Top</VerticalAlignment>\
			<TextFitMode>ShrinkToFit</TextFitMode>\
			<UseFullFontHeight>True</UseFullFontHeight>\
			<Verticalized>False</Verticalized>\
			<StyledText>\
				<Element>\
					<String xml:space="preserve">'+lblGebruik+'</String>\
					<Attributes>\
						<Font Family="Arial" Size="12" Bold="False" Italic="False" Underline="False" Strikeout="False" />\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" HueScale="100" />\
					</Attributes>\
				</Element>\
			</StyledText>\
		</TextObject>\
		<Bounds X="322" Y="150" Width="2880" Height="216" />\
	</ObjectInfo>\
	<ObjectInfo>\
		<TextObject>\
			<Name>TEKST_2</Name>\
			<ForeColor Alpha="255" Red="0" Green="0" Blue="0" />\
			<BackColor Alpha="0" Red="255" Green="255" Blue="255" />\
			<LinkedObjectName />\
			<Rotation>Rotation0</Rotation>\
			<IsMirrored>False</IsMirrored>\
			<IsVariable>False</IsVariable>\
			<GroupID>-1</GroupID>\
			<IsOutlined>False</IsOutlined>\
			<HorizontalAlignment>Left</HorizontalAlignment>\
			<VerticalAlignment>Top</VerticalAlignment>\
			<TextFitMode>ShrinkToFit</TextFitMode>\
			<UseFullFontHeight>True</UseFullFontHeight>\
			<Verticalized>False</Verticalized>\
			<StyledText>\
				<Element>\
					<String xml:space="preserve">'+lblLocatie+'</String>\
					<Attributes>\
						<Font Family="Arial" Size="16" Bold="True" Italic="False" Underline="False" Strikeout="False" />\
						<ForeColor Alpha="255" Red="0" Green="0" Blue="0" HueScale="100" />\
					</Attributes>\
				</Element>\
			</StyledText>\
		</TextObject>\
		<Bounds X="3807" Y="174" Width="911.999999999999" Height="240" />\
	</ObjectInfo>\
</DieCutLabel>';
                label = dymo.label.framework.openLabelXml(testAddressLabelXml);

            }
            //}

            // check that label has an address object
            if (label.getAddressObjectCount() == 0) {
                alert("Selected label does not have an address object on it. Select another label");
                return;
            }

            updatePreview();
            addressTextArea.value = getAddress();
            printButton.disabled = false;
            addressTextArea.disabled = false;
        }
        // loads label file thwn user selects it in file open dialog
        labelFile.onchange = function() {

        };

        // updates address on the label when user types in textarea field
        addressTextArea.onkeyup = function() {
            if (!label) {
                alert('Load label before entering address data');
                return;
            }

            setAddress(addressTextArea.value);
            updatePreview();
        }

        // prints the label
        printButton.onclick = function() {
            try {               

                var element = document.getElementById('list');
                var children = element.children;
                for (var ii = 0; ii < children.length; ii++) {
                    getlabels(ii);
                    if (!label) {
                        alert("Load label before printing");
                        return;
                    }
                    //alert(printersSelect.value);
                    label.print(printersSelect.value);
                    //label.print("unknown printer");
                }

            }
            catch(e)
            {
                alert(e.message || e);
            }
        }

        printersSelect.onchange = populatePrinterDetail;

        // load printers list on startup
        loadPrintersAsync();
    };

    function initTests() {
		if(dymo.label.framework.init) {
			//dymo.label.framework.trace = true;
			dymo.label.framework.init(onload);
		} else {
			onload();
		}
	}

    // register onload event
    if (window.addEventListener)
        window.addEventListener("load", initTests, false);
    else if (window.attachEvent)
        window.attachEvent("onload", initTests);
    else
        window.onload = initTests;

} ());