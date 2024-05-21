<?php
class admin_FotoController extends My_Controller_Action
{


    public function detailAction()
    {
        $id = (int) $this->_getParam('id');
        if (!empty($id)) {
         $fotoModel = new Application_Model_Foto();
         $this->view->foto= $fotoModel->getOne($id);
        }
         parent::detailAction();
    }


    public function ajaxUploadAction() {
        $this->_helper->layout->disableLayout();

        $data = $this->getRequest()->getPost();
        if (empty($_FILES) ) {
                echo 'No files to upload'; exit;
        }
        $fotoModel = new Application_Model_Foto();
        // ini
        $fileElem   = 'Filedata';
        $tempFile   = $_FILES[$fileElem]['tmp_name'];
        $targetPath = $fotoModel->getPathUpload();
        $prefix     = date('d.m.Y.H.i.s').'_';

        $fileNameOrig = $_FILES[$fileElem]['name']; //preg_replace("/(\s|%20)/","_",$_FILES['Filedata']['name']); # replace all white spaces and %20 with _
        $fileName    = $fileNameOrig;
        $fileName    = str_replace(" ","_", $fileName);
        $fileName    = str_replace("(","_", $fileName);
        $fileName    = str_replace(")","_", $fileName);
        $targetFile  = str_replace('//','/',$targetPath) . trim($fileName);
        $response = 0;
        if (move_uploaded_file($tempFile,$targetFile)){             
            $fileData = $v= array(
                            'fileName'     => trim($fileName),
                            'fileNameOrig' => $fileNameOrig,
                            'fileSize'	   => filesize($targetFile),
            );
            $fileId = $fotoModel->saveFile($fileData);
            if (!empty($fileId)){
                $response=1;
            }
    	}
        $this->view->response=$response;
   }

}

