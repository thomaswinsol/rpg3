<?php
$this->jQuery()->onLoadCaptureStart(); ?>



                    $(document).ready(function() {

                        $('#Aantal').focus();
                         /*
			 *  Simple image gallery. Uses default settings
			 */
			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});


        $(document).on("keyup", ".onlyDecimals", function() {
            var newValue = $(this).val().replace(/[^0-9\.,]/g,'');
            $(this).val(newValue);
        });
        
        // Voeg item toe aan winkelmand
       $(".add_to_cart_image_button").click(function(event) {
     	event.preventDefault();
     	$("#divLoading").html('<img alt="Loading" src="/base/images/icons/loader_02.gif" /><BR> Loading, please wait...');
        $(".add_to_cart_image_button").hide();
        var myForm    =  $(this).closest('form');
        var formId    =  myForm.attr('id');
        var formdata  = myForm.serialize();
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?php echo $this->baseUrl() . '/winkelmand/ajax-voegtoe-winkelmand/'; ?>",
          dataType: "html",
          data: {data: formdata },
          success: function(html){
                            if (html == '1') {
                                alert("<?php echo $this->translate('txtAlert1'); ?>");
                            }
                            else {
                                $('.winkelmand').empty().append(html);
                                $('#Aantal').val("");
                                $('#mainTitle').empty().append("<div class='msg_ok'><?php echo ($this->translate('txtToegevoegd')); ?></div>");
                            }
                            $(".add_to_cart_image_button").show();
                            $("#divLoading").html('');
          		}
           });
	   return false;
       });
<?php 
$this->jQuery()->onLoadCaptureEnd();
?>