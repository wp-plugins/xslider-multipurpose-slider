<?php

   $urlEdit = $this->ADMIN_BASE_URL . "?page=xslider-free_admin&ac=edit";

?>

<div class="wrap" ng-cloak style="padding-bottom:100px;">
   <div ng-controller="listCtrl" id="listCtrl" ng-init="init()">

      <div style="width:100%; margin-top:30px; min-width:1080px">

         <?php if ($GLOBALS["xSuccess"] != ""){ ?>
         <div class="boxx" style="margin-bottom:40px; padding:20px 15px 20px 15px; border-left: 10px solid #FDC400; border-right: 10px solid #FDC400; font-size:16px">
            <i class="fa fa-check fa-lg" style="color:#FDC400"></i>
            &nbsp;
            <?php echo $GLOBALS["xSuccess"] ?>
         </div>
         <?php } ?>

         <?php if ($GLOBALS["xError"] != ""){ ?>
         <div class="boxx" style="margin-bottom:40px; padding:20px 15px 20px 15px; border-left: 10px solid #e62727; border-right: 10px solid #e62727; font-size:16px">
            <i class="fa fa-ban fa-lg" style="color:#e62727"></i>
            &nbsp;
            <?php echo $GLOBALS["xError"] ?>
         </div>
         <?php } ?>

         <div class="boxx" style="padding:5px 15px; height:66px; line-height: 64px;">
            <div class="header_logo">
               <img src="<?php echo $this->PLUGIN_URL ?>/admin/images/logo.png">
            </div>
            <div style="float:right;">
               <button type="button" class="xbutton large orange" ng-click="toggleNewsletterPanel()" tooltip title="Be notified about new features, template packs and products.."><i class="fa fa-refresh fa-lg"></i>&nbsp;&nbsp;Stay tuned</button>
            </div>
         </div>
         <div id="newsletterPanel" class="boxx" style="margin-top:1px; padding:0px 15px 0px 15px; overflow:hidden; height:0px">
            <p>
               Leave your email and be notified about <strong>new features</strong>, <strong>template packs</strong>, <strong>free resources</strong> and <strong>new products</strong>
            </p>
            Your Email:
            <input type="text" style="width:300px;" ng-model="globalSettings.newsletterEmail">
            <button type="button" class="xbutton large green" ng-click="joinNewsletter()"><i class="fa fa-newspaper-o fa-lg"></i>&nbsp;&nbsp;JOIN NEWSLETTER</button>
            <div id="joined" style="display:inline-block; margin-left:20px; display:none">
               <i class="fa fa-thumbs-o-up fa-lg" style="font-size:28px; color:#FDC400"></i>&nbsp;&nbsp;Thanks!
            </div>
         </div>

         <div class="boxx" style="margin-top:40px;">
            <div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed #e0e0e0">
               <div class="boxx_title">
                  <div style="display:inline-block; text-align:center; width:30px; padding-right:6px;">
                     <i class="fa fa-sliders fa-lg"></i>
                  </div>
                  Sliders
               </div>
               <div class="space_between"></div>
               <div style="display:inline-block; float:right">
                  <button type="button" class="xbutton green" onclick="document.location='<?php echo $urlEdit ?>'"><i class="fa fa-plus fa-lg"></i>&nbsp;&nbsp;CREATE SLIDER</button>
               </div>
            </div>

            <div class="" ng-if="!projectsLoaded">
               <div style="height:30px; padding: 15px 0 2px 0; text-align:center; font-size:16px; color:#777">
                  <div class="w100">
                     <i class="fa fa-spinner fa-pulse fa-lg fa-fw margin-bottom"></i> loading...
                  </div>
               </div>
            </div>

            <div class="" ng-show="projects.length == 0">
               <div style="height:30px; padding: 2px 0 2px 0; text-align:center">
                  <div class="w100">
                     <i>You haven't created any slider yet. Do it by clicking the above button</i>
                  </div>
               </div>
            </div>

            <div class="" ng-show="projects.length > 0">
               <table width="100%">
                  <thead>
                     <tr>
                        <td width="45"><strong>ID</strong></td>
                        <td><strong>Name</strong></td>
                        <td><strong>Shortcode</strong></td>
                        <td><strong>Google Fonts Used</strong></td>
                        <td width="1"><strong></strong></td>
                     </tr>
                  </thead>
                  <tbody>
                     <tr ng-repeat="project in projects">
                        <td>{{project.id}}</td>
                        <td>{{project.name}}</td>
                        <td>[xsliderfree id={{project.id}}]</td>
                        <td>{{project.usedFonts.join(", ")}}</td>
                        <td nowrap>
                           <button type="button" class="xbutton blue" ng-click="preview(project.id)"><i class="fa fa-play fa-lg"></i>&nbsp;&nbsp;Play</button>
                           <button type="button" class="xbutton green" ng-click="gotoEditPage('<?php echo $urlEdit ?>', project.id)"><i class="fa fa-edit fa-lg"></i>&nbsp;&nbsp;Edit</button>
                           <button type="button" class="xbutton white-red" ng-click="deleteProject(project)"><i class="fa fa-trash-o fa-lg"></i>&nbsp;&nbsp;Delete</button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>



         <div class="boxx" style="margin-top:40px;">
            <div style="">
               <div class="boxx_title" ng-click="updateGlobalsParams('templatePanel', !globals.templatePanel)" style="cursor:pointer; width:100%;">
                  <div style="display:inline-block; text-align:center; width:30px; padding-right:6px;">
                     <i class="fa fa-magic fa-lg"></i>
                  </div>
                  Templates &nbsp;<i class="fa fa-lg" ng-class="globals.templatePanel ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </div>

            <div class="" ng-show="globals.templatePanel" style="padding-top:10px; margin-top:10px; border-top:1px dashed #e0e0e0">
               <div style="padding:20px 0px 30px 0px;text-align:center; font-size:16px;">
                  <i>Template Packs are only available for the premium version of xSlider</i><br><br>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com/template-packs/?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;BROWSE ALL PACKS..</button>
               </div>
            </div>
         </div>



         <div class="boxx" style="margin-top:40px;">
            <div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed #e0e0e0">
               <div class="boxx_title">
                  <div style="display:inline-block; text-align:center; width:30px; padding-right:6px;">
                     <i class="fa fa-google fa-lg"></i>
                  </div>
                  Google Fonts
               </div>
               <div class="space_between"></div>
               <div style="display:inline-block; float:right">
               </div>
            </div>
            <div style="">
               <p>
                  To select a Google Font go to <a href="https://www.google.com/fonts" target="_blank"><strong>https://www.google.com/fonts</strong></a>, choose the font to add, click on the "quick use icon" (the second one), choose the styles you prefer and then copy&paste the entire link inclusion. For Example: <i>&lt;link href='http://fonts.googleapis.com/css?family=Open+Sans:700italic,400,700' rel='stylesheet' type='text/css'&gt;</i>
               </p>
               Add Font:
               <input type="text" style="width:500px;" ng-model="fontToAdd">
               <button type="button" class="xbutton large green" ng-click="addGoogleFont()"><i class="fa fa-edit fa-lg"></i>&nbsp;&nbsp;Add</button>
               <br>
               <br>
               <table width="100%">
                  <tr ng-repeat="item in googleFonts track by $index">
                     <td width="40">
                        <a class="xbutton white-red" href="javascript:void(0);" ng-click="deleteGoogleFont($index)"><i class="fa fa-trash-o fa-lg"></i></a>
                     </td>
                     <td style="padding-left:0px;">
                        <span ng-if="item.loadOnWebsite" style="color:#444">{{item.name}}</span>
                        <span ng-if="!item.loadOnWebsite" style="color:#ccc">{{item.name}}</span>
                     </td>
                     <td width="20"></td>
                     <td style="padding-left:0px;">
                        <span ng-if="item.loadOnWebsite" style="color:#444">{{item.url}}</span>
                        <span ng-if="!item.loadOnWebsite" style="color:#ccc">{{item.url}}</span>
                     </td>
                     <td width="240" align="right">
                        Load on the website
                        <select ng-model="item.loadOnWebsite" ng-options="f.value as f.label for f in selectValues.booleans" ng-change="updateGoogleFont()">
                        </select>
                     </td>
                  </tr>
               </table>
            </div>
         </div>


         <div class="boxx" style="margin-top:40px;">
            <div style="padding-bottom:10px; margin-bottom:10px; border-bottom:1px dashed #e0e0e0">
               <div class="boxx_title">
                  <div style="display:inline-block; text-align:center; width:30px; padding-right:6px;">
                     <i class="fa fa-globe fa-lg"></i>
                  </div>
                  Global Settings
               </div>
               <div class="space_between"></div>
               <div style="display:inline-block; float:right">
               </div>
            </div>
            <div style="">
               <table>
                  <tr>
                     <td>Who can use xSlider</td>
                     <td width="20"></td>
                     <td height="30">
                        <i>This feature is available only in the premium version</i>
                        <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                     </td>
                  </tr>
                  <tr>
                     <td>Place Javascript in Footer <i class="fa fa-info-circle fa-lg" tooltip title="Can be useful for fixing plugin conflicts"></i></td>
                     <td width="20"></td>
                     <td>
                        <select ng-model="globalSettings.jsOnFooter" ng-options="f.value as f.label for f in selectValues.booleans">
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>Load FontAwesome 4 <i class="fa fa-info-circle fa-lg" tooltip title="If your theme or other plugins already load FontAwesome 4 you should avoid loading it twice by selecting the 'No' option"></i></td>
                     <td width="20"></td>
                     <td>
                        <select ng-model="globalSettings.loadAwesome" ng-options="f.value as f.label for f in selectValues.booleans">
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td>Load TweenMax <i class="fa fa-info-circle fa-lg" tooltip title="If your theme or other plugins already load TweenMax you should avoid loading it twice by selecting the 'No' option"></i></td>
                     <td width="20"></td>
                     <td>
                        <select ng-model="globalSettings.loadTweenMax" ng-options="f.value as f.label for f in selectValues.booleans">
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td></td>
                     <td width="20"></td>
                     <td style="padding-top:10px;">
                        <button type="button" class="xbutton green" ng-click="updateGlobalSettings()"><i class="fa fa-gear fa-lg"></i>&nbsp;&nbsp;Save Settings</button>
                        <div id="globals_saved" style="display:inline-block; margin-left:20px; display:none">
                           <i class="fa fa-thumbs-o-up fa-lg" style="font-size:28px; color:#FDC400"></i>&nbsp;&nbsp;settings have been saved!
                        </div>
                     </td>
                  </tr>
               </table>
            </div>
         </div>


      </div>


      <div id="overlay" ng-click="closePopup(currentPopupCloseCallback)">
      </div>

      <div id="popup_import" class="popup">
         <div style="padding:20px; text-align:center">
            <h1>Slider Import</h1>
            <?php

               if (!class_exists("ZipArchive"))
               {

            ?>
            <p>
               <strong>To be able to import sliders the ZipArchive php extension have to be installed. Please contact your webserver administrator.</strong>
            </p>
            <?php

               }
               else
               {

            ?>
            <form action="<?php echo $this->ADMIN_BASE_URL . "?page=xslider-free_admin&ac=list"; ?>" enctype="multipart/form-data" method="post">
               <input type="hidden" name="action" value="importSlider">
               <p>
                  Please choose the zip file to import
                  &nbsp;&nbsp;
                  <button type="button" ng-click="importSelectFile()" class="xbutton green"><i class="fa fa-folder-open-o fa-lg"></i>&nbsp;&nbsp;Select File</button>
               </p>
               <input id="fileSelector" type="file" name="file" style="display:none" onchange="angular.element(this).scope().importFileSelected(this.files)"/>
               <p ng-show="fileToImport">
                  Selected file: <strong>{{fileToImport.name}}</strong>
               </p>
               <br>
               <span ng-if="importingSlide">
                  <i class="fa fa-spinner fa-pulse fa-lg fa-fw margin-bottom"></i> Importing...
                  <br>
                  <br>
               </span>
               <button type="submit" class="xbutton orange" ng-click="importingSlide = true" ng-show="fileToImport"><i class="fa fa-sign-in fa-lg"></i>&nbsp;&nbsp;IMPORT</button>
               <button type="button" class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
            </form>
            <?php

               }

            ?>
         </div>
      </div>

      <div id="popup_preview" class="popup">
         <div style="padding:20px; text-align:center">
            <button class="xbutton gray" ng-click="closePreview()" style="margin-top:10px;"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE PREVIEW</button>
            <div id="preview_container"></div>
         </div>
      </div>

      <div id="popup_confirm" class="popup">
         <div style="padding:20px; text-align:center">
            <span ng-bind-html="popup_confirm_text"></span>
            <button class="xbutton blue" ng-click="popup_confirm_do()"><i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;OK</button>
            <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
         </div>
      </div>


   </div>
</div>
