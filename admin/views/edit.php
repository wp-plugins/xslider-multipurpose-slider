<script>
<?php

   echo "var isSsl = " . (is_ssl() ? "true" : "false") . ";";

   if (isset($_REQUEST["tpl"]) && $_REQUEST["tpl"])
   {
      echo "var idProject = undefined;\n";
      echo "var getTemplate = true;\n";
      echo "var idTemplate = '" . $_REQUEST["id"] . "';\n";
      $mode = "create";
      $title = "Create new Slider from Template";
   }
   else if (isset($_REQUEST["id"]) && $_REQUEST["id"])
   {
      echo "var getTemplate = false;\n";
      echo "var idProject = " . $_REQUEST["id"] . ";\n";
      $mode = "update";
      $title = "Edit Slider";
   }
   else
   {
      echo "var getTemplate = false;\n";
      echo "var idProject = undefined;\n";
      $mode = "create";
      $title = "Create new Slider";
   }


   $urlList = $this->ADMIN_BASE_URL . "?page=xslider-free_admin&ac=list";

?>
</script>
<div class="wrap" ng-cloak style="padding-bottom:100px;">
<div ng-controller="editCtrl" id="editCtrl" ng-init="init()">
<form name="editForm" ng-mousedown="dirtyWatch()">

   <div style="width:100%; margin-top:30px; min-width:1080px" ng-if="project">

      <div class="boxx" style="padding:5px 15px; height:66px; line-height: 64px;">
         <div class="header_logo">
            <img src="<?php echo $this->PLUGIN_URL ?>/admin/images/logo.png" onclick="document.location='<?php echo $urlList ?>'" style="cursor:pointer">
         </div>
         <div style="float:right;">
            <span class="header_title"><?php echo $title ?></span>
            <button type="button" class="xbutton large gray" onclick="document.location='<?php echo $urlList ?>'"><i class="fa fa-angle-left fa-lg"></i>&nbsp;&nbsp;Back to slider list</button>
         </div>
      </div>

      <div class="boxx" style="margin-top:30px;">
         Project Name <input type="text" ng-model="project.name" style="width:220px">
         <div class="space_between"></div>
         <button type="button" class="xbutton large gray" ng-click="toggleProjectSettings()"><i class="fa fa-gear fa-lg"></i>&nbsp;&nbsp;Project Settings&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-lg" ng-class="project.ui.showProjectSettings ? 'fa-angle-down' : 'fa-angle-right'"></i></button>
         <div style="float:right;">
            <button type="button" class="xbutton large green" ng-click="saveProject()"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;SAVE</button>
         </div>
         <div class="" style="clear:both; padding-top:15px; background:#fff" ng-show="project.ui.showProjectSettings">

            <h4 class="panel_head" ng-click="togglePanel(project.ui.projectSettingsPanels, 'embed')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-code fa-lg"></i></div>Embedding Methods
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.embed ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.embed && !idProject" style="margin-top:20px; padding: 0 15px 10px 15px;">
               Once saved, here you'll find how to add the slider to your website
            </div>
            <div ng-show="project.ui.projectSettingsPanels.embed && idProject" style="margin-top:20px; padding: 0 15px 10px 15px;">
               <div class="xrow first">
                  <div class="xrow_label">Shortcode</div>
                  [xsliderfree id={{idProject}}]
               </div>
               <div class="xrow">
                  <div class="xrow_label">Php</div>
                  <div style="display:inline-block; width:250px;">
                     &lt;?php showXSliderFree({{idProject}}) ?&gt;
                  </div>
                  <span class="unit">to show the slider on each page</span>
               </div>
               <div class="xrow first">
                  <div class="xrow_label"></div>
                  <div style="display:inline-block; width:250px;">
                     &lt;?php showXSlider({{idProject}}, "10, 15, 34") ?&gt;
                  </div>
                  <span class="unit">to show the slider only on certain pages (type them separated by commas)</span>
                   <i class="fa fa-info-circle fa-lg" tooltip title="You can find the page or post ID inside the url - i.e. http://yourdomain.com/wp-admin/post.php?post=ID&action=edit"></i>
               </div>
               <div class="xrow">
                  <div class="xrow_label">Widget</div>
                  <i>This feature is available only in the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>
            </div>





            <h4 class="panel_head" ng-click="togglePanel(project.ui.projectSettingsPanels, 'layout')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-laptop fa-lg"></i></div>Layout
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.layout ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.layout" style="float:left; width:100%; margin-top:30px; padding: 0 15px 10px 15px;">

               <div class="layout_preview">
                  <div class="layout_preview_slider">
                     <div class="layout_label_slider">SLIDER</div>
                     <div class="layout_preview_slide_bg" ng-style="getLayoutPreviewSlideBgStyle()">
                        <div class="layout_label_slide_bg">SLIDE BACKGROUND</div>
                        <div class="layout_preview_slide_content" ng-style="getLayoutPreviewSlideContentStyle()">
                           <div class="layout_label_slide_content">SLIDE CONTENT</div>
                        </div>
                     </div>
                  </div>
               </div>

               <div style="margin-left:280px; padding-top:13px;">
                  <table>
                     <tr>
                        <td width="110">Size</td>
                        <td width="20"></td>
                        <td colspan="3" style="white-space: nowrap;">
                           Width
                           <islider min="300" max="1200" step="10" ng-model="project.width" ng-slider-change="" style="width:96px!important"></islider>
                           <input type="text" ng-model="project.width" style="width:50px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Height
                           <islider min="200" max="800" step="10" ng-model="project.height" ng-slider-change="" style="width:96px!important"></islider>
                           <input type="text" ng-model="project.height" style="width:50px;"> <span class="unit">px</span>
                        </td>
                     </tr>
                     <tr>
                        <td>Slider</td>
                        <td width="20"></td>
                        <td>
                           <select ng-model="project.layout">
                              <option value="fixed">Fixed</option>
                              <option value="responsive">Responsive</option>
                           </select>
                        </td>
                        <td width="20"></td>
                        <td>
                           <div style="display:inline-block" ng-show="project.layout == 'responsive'">
                              Max Width <i class="fa fa-info-circle fa-lg" tooltip title="the maximum width of the slider"></i>
                              <input type="text" ng-model="project.layoutMaxWidth" style="width:50px;"> <span class="unit">px</span>
                              <div class="space_between"></div>
                              Min Width <i class="fa fa-info-circle fa-lg" tooltip title="the minimum width of the slider"></i>
                              <input type="text" ng-model="project.layoutMinWidth" style="width:50px;"> <span class="unit">px</span>
                           </div>
                        </td>
                     </tr>
                     <tr ng-show="project.layout == 'responsive'">
                        <td>Slide Background</td>
                        <td width="20"></td>
                        <td>
                           <select ng-model="project.layoutSlideContainer">
                              <option value="fixed">Fixed</option>
                              <option value="responsive">Responsive</option>
                           </select>
                        </td>
                        <td width="20"></td>
                        <td>
                           <div style="display:inline-block" ng-show="project.layoutSlideContainer == 'responsive'">
                              Max Width <i class="fa fa-info-circle fa-lg" tooltip title="the maximum width of the each slide background"></i>
                              <input type="text" ng-model="project.layoutSlideContainerMaxWidth" style="width:50px;"> <span class="unit">px</span>
                              <div class="space_between"></div>
                              Min Width <i class="fa fa-info-circle fa-lg" tooltip title="the minimum width of the each slide background"></i>
                              <input type="text" ng-model="project.layoutSlideContainerMinWidth" style="width:50px;"> <span class="unit">px</span>
                           </div>
                        </td>
                     </tr>
                     <tr ng-show="project.layout == 'responsive' && project.layoutSlideContainer == 'responsive'">
                        <td>Slide Content <i class="fa fa-warning fa-lg" style="color:#e62727" tooltip title="In the editor the slide content is always fixed. If you choose the 'Responsive' option and want to view the result, click on the PLAY button to show the preview"></i></td>
                        <td width="20"></td>
                        <td>
                           <select ng-model="project.layoutSlide">
                              <option value="fixed">Fixed</option>
                              <option value="responsive">Responsive</option>
                           </select>
                        </td>
                        <td width="20"></td>
                        <td>
                           <div style="display:inline-block" ng-show="project.layoutSlide == 'responsive'">
                              Max Width <i class="fa fa-info-circle fa-lg" tooltip title="the maximum width of the each slide content"></i>
                              <input type="text" ng-model="project.layoutSlideMaxWidth" style="width:50px;"> <span class="unit">px</span>
                              <div class="space_between"></div>
                              Min Width <i class="fa fa-info-circle fa-lg" tooltip title="the minimum width of the each slide content"></i>
                              <input type="text" ng-model="project.layoutSlideMinWidth" style="width:50px;"> <span class="unit">px</span>
                           </div>
                        </td>
                     </tr>
                  </table>
               </div>
            </div>






            <h4 class="panel_head" style="clear:both;" ng-click="togglePanel(project.ui.projectSettingsPanels, 'settings')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-gears fa-lg"></i></div>Settings
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.settings ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.settings" style="margin-top:20px; padding: 0 15px 10px 15px;">

               <div class="xrow first">

                  Perspective
                  <islider min="1" max="2000" step="10" ng-model="project.perspective" ng-slider-change=""></islider>
                  <input type="text" ng-model="project.perspective" style="width:50px;"> <span class="unit">px</span>
                  <div class="space_between"></div>
               </div>

               <div class="xrow">
                  Start
                  <select ng-model="project.start">
                     <option value="ready">on document ready</option>
                     <option value="load">after all page elements have been loaded</option>
                     <option value="visible">when the slider is visible (i.e. scrolling the page)</option>
                  </select>
                  <div style="display:inline-block" ng-show="project.start == 'visible'">
                     <div class="space_between"></div>
                     Visibility Threshold <i class="fa fa-info-circle fa-lg" tooltip title="the minimum number of visible pixels (height) required to start the slider"></i>
                     <islider min="0" max="500" step="1" ng-model="project.visibleTreshold" ng-slider-change=""></islider>
                     <input type="text" ng-model="project.visibleTreshold" style="width:50px;"> <span class="unit">px</span>
                  </div>
                  <div class="space_between"></div>
                  Delay <i class="fa fa-info-circle fa-lg" tooltip title="how long to wait for before starting the slider"></i>
                  <islider min="0" max="5.0000001" step="0.1" ng-model="project.delay" ng-slider-change=""></islider>
                  <input type="text" ng-model="project.delay" style="width:40px;"> <span class="unit">sec</span>
               </div>
            </div>




            <h4 class="panel_head" style="clear:both" ng-click="togglePanel(project.ui.projectSettingsPanels, 'appearance')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-tint fa-lg"></i></div>Main Look
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.appearance ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.appearance" style="margin-top:20px; padding: 0 15px 10px 15px;">

               <div class="xrow first">
                  Background
                  <select ng-model="project.bgType">
                     <option value="image">Image</option>
                     <option value="external">External URL</option>
                     <option value="color">Solid Color</option>
                     <option value="none">Transparent</option>
                  </select>

                  <div style="display:inline-block" ng-show="project.bgType == 'image'">
                     <div class="space_between"></div>
                     Image
                     <button type="button" class="xbutton blue" ng-click="projectBgSelectImage()"><i class="fa fa-image fa-lg"></i>&nbsp;&nbsp;Select Image</button>
                  </div>

                  <div style="display:inline-block" ng-show="project.bgType == 'external'">
                     <div class="space_between"></div>
                     Url
                     <input type="text" ng-model="project.bgUrl" style="width:175px">
                  </div>

                  <div style="display:inline-block" ng-show="project.bgType == 'image' || project.bgType == 'external'">
                     <div class="space_between"></div>
                     Fit
                     <select ng-model="project.bgFit">
                        <option value="">None</option>
                        <option value="contain">Contain</option>
                        <option value="cover">Cover</option>
                     </select>
                  </div>

                  <div style="display:inline-block" ng-show="project.bgType == 'image' || project.bgType == 'external'">
                     <div class="space_between"></div>
                     Repeat
                     <select ng-model="project.bgRepeat">
                        <option value="no-repeat">no-repeat</option>
                        <option value="repeat">repeat</option>
                        <option value="repeat-x">repeat-x</option>
                        <option value="repeat-y">repeat-y</option>
                     </select>
                  </div>

                  <div style="display:inline-block" ng-show="project.bgType == 'image' || project.bgType == 'external'">
                     <div class="space_between"></div>
                     Position
                     <select ng-model="project.bgPosition">
                        <option value="center top">center top</option>
                        <option value="center center">center center</option>
                        <option value="center bottom">center bottom</option>
                        <option value="left top">left top</option>
                        <option value="left center">left center</option>
                        <option value="left bottom">left bottom</option>
                        <option value="right top">right top</option>
                        <option value="right center">right center</option>
                        <option value="right bottom">right bottom</option>
                     </select>
                  </div>

                  <div style="display:inline-block" ng-show="project.bgType != 'none'">
                     <div class="space_between"></div>
                     Color
                     <spectrum-colorpicker ng-model="project.bgColor" options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:true, preferredFormat:'hex'}"></spectrum-colorpicker>
                  </div>
               </div>

               <div class="xrow">
                  Edit the Slider Css
                  <div class="space_between"></div>
                  <i style="font-size:14px">The feature are only available for the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>

            </div>







            <h4 class="panel_head" style="color: #aaa;" ng-click="togglePanel(project.ui.projectSettingsPanels, 'mobile')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-mobile-phone fa-lg" style="font-size: 1.7em!important;"></i></div>Mobile
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.mobile ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.mobile" style="margin-top:20px; padding: 0 15px 10px 15px; position:relative">

               <div class="xrow first">
                  <i style="font-size:14px">Mobile settings are only available for the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>


            </div>








            <h4 class="panel_head" style="color: #aaa;" ng-click="togglePanel(project.ui.projectSettingsPanels, 'arrows')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-chevron-circle-right fa-lg"></i></div>Arrows
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.arrows ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.arrows" style="margin-top:20px; padding: 0 15px 10px 15px; position:relative">

               <div class="xrow first">
                  <i style="font-size:14px">Arrow settings are only available for the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>


            </div>



            <h4 class="panel_head" style="color: #aaa;" ng-click="togglePanel(project.ui.projectSettingsPanels, 'dots')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-ellipsis-h fa-lg"></i></div>Bullets
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.dots ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.dots" style="margin-top:20px; padding: 0 15px 10px 15px; position:relative">

               <div class="xrow first">
                  <i style="font-size:14px">Bullet settings are only available for the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>

            </div>






            <h4 class="panel_head" style="color: #aaa;" ng-click="togglePanel(project.ui.projectSettingsPanels, 'preload')">
               <div style="display:inline-block; width:27px; text-align:center; padding-right:10px;"><i class="fa fa-download fa-lg"></i></div>Preload
               <div style="float:right; display:inline-block">
                  <i class="fa fa-lg" ng-class="project.ui.projectSettingsPanels.preload ? 'fa-angle-down' : 'fa-angle-right'"></i>
               </div>
            </h4>
            <div ng-show="project.ui.projectSettingsPanels.preload" style="margin-top:20px; padding: 0 15px 10px 15px; position:relative">

               <div class="xrow first">
                  <i style="font-size:14px">Preload settings are only available for the premium version</i>
                  <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
               </div>
            </div>
         </div>
      </div>

      <div style="margin-top:30px;">

         <div ng-style="getPreviewStyle()">

            <div id="stage" ng-style="getStageStyle()">

               <div id="slide{{currentSlide.id}}_cont"
                    ng-style="getSlideContainerStyle(currentSlide)"
                    ng-click="onSlideClick(currentSlide);">

                  <div id="slide{{currentSlide.id}}_bg" ng-style="getSlideBgStyle(currentSlide)"></div>

                  <div id="slide{{currentSlide.id}}"
                       ng-style="getSlideStyle(currentSlide)">

                     <div ng-repeat="element in currentSlide.elements.slice().reverse()"
                          ng-if="element.visible"
                          ng-style="getElementPosition(element, $index)"
                          id="element{{element.id}}_pos"
                          draggable on-drag="onDrag(ui, element)" locked="element.locked" snap-to-grid="project.ui.snapToGrid" grid-width="project.ui.gridWidth" grid-height="project.ui.gridHeight"
                          ng-click="selectElement(element, $index); $event.stopPropagation()">

                        <div id="element{{element.id}}_cont"
                             ng-style="getElementContainerStyle(element)">

                           <div ng-if="element.type == 'text' && element.text.contentType == 'html'" ng-style="getElementTextStyle(element)" ng-bind-html="element.text.content | nl2br:element.text.nl2br | unsafe" id="element{{element.id}}_text" class="el_type_text"></div>
                           <div ng-if="element.type == 'text' && (element.text.contentType == 'plain' || element.text.contentType == '')" ng-style="getElementTextStyle(element)" id="element{{element.id}}_text" class="el_type_text">{{element.text.content}}</div>

                           <div ng-if="element.type == 'fontIcon'" ng-style="getElementFontIconStyle(element)" ng-bind-html="getFontIcon(element) | unsafe" id="element{{element.id}}_font_icon" class="el_type_font_icon"></div>

                           <div ng-if="element.type == 'image'" resizable aspect-ratio="element.image.aspectRatio" on-resize="onImageResize(ui, element)">
                              <img ng-src="{{element.path}}" ng-style="getElementImageStyle(element)" id="element{{element.id}}_image" style="width:100%; height:100%">
                           </div>

                           <div ng-if="element.type == 'youtube'">
                              <iframe ng-style="getElementYoutubeStyle(element)" id="element{{element.id}}_youtube" ng-src="{{getYoutubeUrl(element) | trustAsResourceUrl}}" frameborder="0" allowfullscreen></iframe>
                              <div style="position:absolute; z-index:99999; top:0; left:0; width:{{element.youtube.width}}px; height:{{element.youtube.height}}px;"></div>
                           </div>

                           <div ng-if="element.type == 'vimeo'">
                              <iframe ng-style="getElementVimeoStyle(element)" id="element{{element.id}}_vimeo" ng-src="{{getVimeoUrl(element) | trustAsResourceUrl}}" width="{{element.vimeo.width}}" height="{{element.vimeo.height}}" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                              <div style="position:absolute; z-index:99999; top:0; left:0; width:{{element.vimeo.width}}px; height:{{element.vimeo.height}}px;"></div>
                           </div>

                           <div ng-if="element.type == 'video'" style="width:{{element.video.width}}px; height:{{element.video.height}}px">
                              <video id="element{{element.id}}_video"
                                     ng-style="getElementVideoStyle(element)"
                                     width="{{element.video.width}}"
                                     height="{{element.video.height}}"
                                     video-attributes="element.video">
                                 <source ng-src="{{element.video.mp4Url | trustAsResourceUrl}}" type="video/mp4">
                                 <source ng-src="{{element.video.webmUrl | trustAsResourceUrl}}" type="video/webm">
                                 <source ng-src="{{element.video.ogvUrl | trustAsResourceUrl}}" type="video/ogg">
                              </video>
                              <div style="position:absolute; z-index:99999; top:0; left:0; width:{{element.video.width}}px; height:{{element.video.height}}px;"></div>
                           </div>

                           <div ng-if="element.type == 'empty'" resizable on-resize="onEmptyResize(ui, element)"
                                ng-style="getElementEmptyStyle(element)" id="element{{element.id}}_empty">
                           </div>

                           <div ng-if="element.type == 'custom'">
                              <style ng-bind-html="element.custom.css | unsafe"></style>
                              <div ng-bind-html="element.custom.html | unsafe"></div>
                              <div ng-style="getElementCustomOverlay(element)" id="element{{element.id}}_custom_overlay"></div>
                           </div>

                        </div>

                        <div id="element{{element.id}}_move"
                             ng-if="element.showMove"
                             ng-style="getMoveElementStyle(element)"
                             draggable on-drag="onDragMove(ui, element)">
                           Move
                        </div>

                     </div>

                     <div ng-repeat="row in getGridRows()" ng-style="getGridRowStyle(row)" ng-show="project.ui.showGrid"></div>
                     <div ng-repeat="col in getGridCols()" ng-style="getGridColStyle(col)" ng-show="project.ui.showGrid"></div>

                     <div ng-style="getLeftBorderStyle()"></div>
                     <div ng-style="getRightBorderStyle()"></div>

                  </div>

               </div>

               <div id="x_left_arrow" ng-style="getArrowContainerStyle('left')" ng-show="project.ui.showArrows && project.arrows.source != 'none'">
                  <i class="fa" ng-class="project.arrows.type.replace('right','left')" ng-style="getArrowStyle()" ng-show="project.arrows.source == 'icon'"></i>
                  <img ng-src="{{project.arrows.leftImage}}" ng-show="project.arrows.source == 'image'">
               </div>
               <div id="x_right_arrow" ng-style="getArrowContainerStyle('right')" ng-show="project.ui.showArrows && project.arrows.source != 'none'">
                  <i class="fa" ng-class="project.arrows.type" ng-style="getArrowStyle()" ng-show="project.arrows.source == 'icon'"></i>
                  <img ng-src="{{project.arrows.rightImage}}" ng-show="project.arrows.source == 'image'">
               </div>
               <div id="x_dots" ng-style="getDotsStyle()" ng-show="project.ui.showDots && project.dots.source != 'none'">
                  <div ng-repeat="slide in project.slides" ng-style="getDotContainerStyle(slide)">
                     <i class="fa" ng-class="project.dots.iconOff" ng-style="getDotIconStyle()" ng-show="project.dots.source == 'icon'"></i>
                     <img ng-src="{{project.dots.offImage}}" ng-show="project.dots.source == 'image'">
                  </div>
               </div>

            </div>

         </div>
      </div>

      <div style="margin-top: 1px; text-align:center;background: white;padding: 15px 0;">
         <span tooltip title="Show/hide rulers grid">
            Grid
            <select ng-model="project.ui.showGrid" ng-options="f.value as f.label for f in selectValues.booleans"></select>
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Space between rulers in pixel">
            Size
            <input type="text" ng-model="project.ui.gridWidth" style="width:40px;">
            x
            <input type="text" ng-model="project.ui.gridHeight" style="width:40px;">
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Rulers color">
            Color
            <spectrum-colorpicker
              ng-model="project.ui.gridColor"
              options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:true, preferredFormat:'hex'}">
            </spectrum-colorpicker>
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Snap elements to rulers">
            Snap
            <select ng-model="project.ui.snapToGrid" ng-options="f.value as f.label for f in selectValues.booleans"></select>
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Show/hide arrows during editing">
            Arrows
            <select ng-model="project.ui.showArrows" ng-options="f.value as f.label for f in selectValues.booleans"></select>
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Show/hide bullets during editing">
            Bullets
            <select ng-model="project.ui.showDots" ng-options="f.value as f.label for f in selectValues.booleans"></select>
         </span>
         <div class="space_between_less"></div>
         <span tooltip title="Move elements using the keyboard arrows">
            Move with keyboard
            <select ng-model="project.ui.arrowsMove" ng-options="f.value as f.label for f in selectValues.booleans"></select>
         </span>
         <div class="space_between_less"></div>
         <button type="button" class="xbutton blue" ng-click="preview(currentSlideIdx, true)"><i class="fa fa-play"></i> PLAY SLIDE</button>
         <button type="button" class="xbutton blue" ng-click="preview(0, false)"><i class="fa fa-play"></i> PLAY ALL</button>
      </div>

      <div class="" style="width:100%; min-width: 960px;">

         <div class="" style="width:300px; float:left">

            <div class="" style="width:100%;">
               <div class="boxx" style="height:200px;">
                  <div style="padding-bottom:20px;">

                     <div style="display:inline-block; font-size:18px; ">
                        Slides
                     </div>
                     <div style="display:inline-block; float:right">
                        <button type="button" class="xbutton green" ng-click="addEmptySlide()"><i class="fa fa-sliders fa-lg"></i> Empty</button>
                        <button type="button" class="xbutton green" ng-click="addSlidesFromLibrary()"><i class="fa fa-picture-o fa-lg"></i> Images</button>
                     </div>

                  </div>
                  <div class="slide_list" style="height:150px;">
                     <div ui-sortable="sortableSlidesOptions" ng-model="project.slides" style="min-height:150px">
                        <div class="slide_row" ng-repeat="item in project.slides" ng-click="selectSlide(item, $index)" ng-class="currentSlide == item ? 'curr' : ''">
                           <div style="float:left; width:210px; white-space:nowrap; overflow:hidden; color:#000; padding-left:3px">{{$index+1}}.&nbsp;&nbsp; {{item.name}}</div>
                           <div style="float:right; padding-right:5px; color:#000">
                              <i class="fa fa-copy" style="color:#aaa" tooltip title="The duplication feature is only available for the premium version of xSlider"></i>
                              <i class="fa fa-trash-o" ng-click="deleteSlide($index)" ng-show="project.slides.length > 1" tooltip title="Delete Slide" style="margin-left:3px;"></i>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>


            <div class="" style="width:100%">
               <div class="boxx" style="margin-top:20px;">

                  <h4 class="panel_head" ng-click="togglePanel(project.ui.slidePanels, 'slide')">
                     Slide
                     <div style="float:right; display:inline-block">
                        <i class="fa fa-lg" ng-class="project.ui.slidePanels.slide ? 'fa-angle-down' : 'fa-angle-right'"></i>
                     </div>
                  </h4>
                  <div ng-show="project.ui.slidePanels.slide" style="padding:10px 5px 10px 5px">
                     <div class="xrow first">
                        Name
                        <input type="text" ng-model="currentSlide.name" style="width:210px;">
                     </div>
                     <div class="xrow">
                        Duration
                        <islider min="6" max="40" step="1" ng-model="currentSlide.time" ng-slider-change=""></islider>
                        <input type="text" ng-model="currentSlide.time" style="width:35px; margin-left:10px;"> <span class="unit">sec</span><br>
                     </div>
                     <div class="xrow first">
                        <button type="button" class="xbutton green" ng-click="changeSlideDuration()" tooltip title="You will be prompted to insert the new duration. All the elements will maintain their speeds, their durations will be re-computed based on the new value"><i class="fa fa-long-arrow-right fa-lg"></i>&nbsp;&nbsp;Change duration preserving speeds</button>
                     </div>
                     <div class="xrow">
                        At the end
                        <select ng-model="currentSlide.atTheEnd" style="width:184px">
                           <option value="gotoNext">Go to next slide</option>
                           <option value="none">Do nothing</option>
                        </select>
                     </div>
                     <div class="xrow" style="padding-top:15px">
                        <button type="button" class="xbutton orange" style="width:100%; opacity:0.5"><i class="fa fa-upload fa-lg"></i>&nbsp;&nbsp;Copy the Current Slide</button>
                        <div style="padding-top:5px;"><strong>NOTE</strong>: copying slides between sliders is only available for the premium version of xSlider <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button></div>
                     </div>
                  </div>


                  <h4 class="panel_head" ng-click="togglePanel(project.ui.slidePanels, 'animation')">
                     Animation
                     <div style="float:right; display:inline-block">
                        <i class="fa fa-lg" ng-class="project.ui.slidePanels.animation ? 'fa-angle-down' : 'fa-angle-right'"></i>
                     </div>
                  </h4>
                  <div ng-show="project.ui.slidePanels.animation" style="padding:10px 5px 10px 5px">
                     <div class="xrow first">
                        <select ng-model="currentSlide.fx" ng-change="previewSlideFx()" ng-options="item as (item | removeStr:'enter.') for item in slideEffects">
                        </select>
                     </div>
                     <div class="xrow">
                        Duration
                        <islider min="0" max="3.000001" step="0.1" ng-model="currentSlide.fxTime" ng-slider-change=""></islider>
                        <input type="text" ng-model="currentSlide.fxTime" style="width:35px; margin-left:10px;"> <span class="unit">sec</span>
                     </div>

                     <div class="slide_fx_container">
                        <div id="slideAnimPreview" ng-click="previewSlideFx()" style="position:absolute; top:40px; left:50px; width:160px; height:100px; margin:0 auto; overflow:hidden">
                           <div id="slideAnimPreview1" style="position:absolute; top:0; left:0; width:160px; height:100px; line-height:100px; background:#FDC400; color:#fff; text-align:center; font-size:24px">B</div>
                           <div id="slideAnimPreview2" style="position:absolute; top:0; left:0; width:160px; height:100px; line-height:100px; background:#000; color:#fff; text-align:center; font-size:24px">A</div>
                        </div>
                        <div style="position:absolute; bottom:15px; left:100px;">
                           <button type="button" class="xbutton blue" ng-click="previewSlideFx()"><i class="fa fa-play"></i> PLAY</button>
                        </div>
                     </div>
                  </div>


                  <h4 class="panel_head" ng-click="togglePanel(project.ui.slidePanels, 'bg')">
                     Background
                     <div style="float:right; display:inline-block">
                        <i class="fa fa-lg" ng-class="project.ui.slidePanels.bg ? 'fa-angle-down' : 'fa-angle-right'"></i>
                     </div>
                  </h4>
                  <div ng-show="project.ui.slidePanels.bg" style="padding:10px 5px 10px 5px">
                     <div class="xrow first">
                        <div class="xrow_label_side">Background</div>
                        <select ng-model="currentSlide.bgType" style="width:160px">
                           <option value="image">Image</option>
                           <option value="external">External URL</option>
                           <option value="color">Solid Color</option>
                           <option value="none">Transparent</option>
                        </select>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'image'">
                        <div class="xrow_label_side">Image</div>
                        <button type="button" class="xbutton blue" ng-click="slideBgSelectImage()"><i class="fa fa-image fa-lg"></i>&nbsp;&nbsp;Select Image</button>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'external'">
                        <div class="xrow_label_side">Url</div>
                        <input type="text" ng-model="currentSlide.bgUrl" style="width:160px">
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'image' || currentSlide.bgType == 'external'">
                        <div class="xrow_label_side">Fit</div>
                        <select ng-model="currentSlide.bgFit" style="width:160px">
                           <option value="">None</option>
                           <option value="contain">Contain</option>
                           <option value="cover">Cover</option>
                        </select>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'image' || currentSlide.bgType == 'external'">
                        <div class="xrow_label_side">Repeat</div>
                        <select ng-model="currentSlide.bgRepeat" style="width:160px">
                           <option value="no-repeat">no-repeat</option>
                           <option value="repeat">repeat</option>
                           <option value="repeat-x">repeat-x</option>
                           <option value="repeat-y">repeat-y</option>
                        </select>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'image' || currentSlide.bgType == 'external'">
                        <div class="xrow_label_side">Position</div>
                        <select ng-model="currentSlide.bgPosition" style="width:160px">
                           <option value="center top">center top</option>
                           <option value="center center">center center</option>
                           <option value="center bottom">center bottom</option>
                           <option value="left top">left top</option>
                           <option value="left center">left center</option>
                           <option value="left bottom">left bottom</option>
                           <option value="right top">right top</option>
                           <option value="right center">right center</option>
                           <option value="right bottom">right bottom</option>
                        </select>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType != 'none'">
                        <div class="xrow_label_side">Color</div>
                        <spectrum-colorpicker
                          ng-model="currentSlide.bgColor"
                          options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:true, preferredFormat:'hex'}">
                        </spectrum-colorpicker>
                     </div>
                     <div class="xrow" ng-show="currentSlide.bgType == 'image' || currentSlide.bgType == 'external'">
                        <div class="xrow_label_side" style="color:#aaa">
                           Overlay
                           <i class="fa fa-info-circle fa-lg" tooltip title="If your image is small you can activate this feature to apply a pixel pattern over it and hide imperfections due to stretch. You can also choose to show the overlay when the slide width exceeds a certain value. Leave it blank if you wish it to be always visible."></i>
                        </div><br><br>
                        <i>This feature is only available for the premium version of xSlider</i><br><br>
                        <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                     </div>
                  </div>


                  <h4 class="panel_head" style="color:#aaa" ng-click="togglePanel(project.ui.slidePanels, 'ken')">
                     Ken Burns (pan, zoom & rotate)
                     <div style="float:right; display:inline-block">
                        <i class="fa fa-lg" ng-class="project.ui.slidePanels.ken ? 'fa-angle-down' : 'fa-angle-right'"></i>
                     </div>
                  </h4>
                  <div ng-show="project.ui.slidePanels.ken" style="padding:10px 5px 10px 5px">
                     <div class="xrow first">
                        <i>Ken Burns Effects is only available for the premium version</i><br><br>
                        <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                     </div>
                  </div>

               </div>
            </div>

         </div>

         <div class="" style="margin-left: 330px;">

            <div class="boxx" style="margin-top:30px; position:relative" ng-show="currentSlide">

                  <div ng-show="elementButtons" ng-mouseleave="elementButtons = false"  style="background:#fff; border:1px solid #ccc; padding:25px; border-radius:3px; position:absolute; top:0px; right:0; box-shadow:3px 3px 6px rgba(0,0,0,0.5); z-index:9999; line-height:10px">
                     <button type="button" style="width:130px" class="xbutton green" ng-click="addElement('text')"><i class="fa fa-font fa-lg"></i>&nbsp;&nbsp;Text</button>
                     <div class="space_between_less"></div>
                     <button type="button" style="width:130px" class="xbutton green" ng-click="addElement('image')"><i class="fa fa-picture-o fa-lg"></i>&nbsp;&nbsp;Image</button>
                     <div class="space_between_less"></div>
                     <button type="button" style="width:130px" class="xbutton green" ng-click="addElement('font_icon')"><i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;Icon</button>
                     <br>
                     <br>
                     <button type="button" style="width:130px" class="xbutton green" ng-click="addElement('empty')"><i class="fa fa-square-o fa-lg"></i>&nbsp;&nbsp;Empty</button>
                     <div class="space_between_less"></div>
                     <button type="button" style="width:130px; opacity:0.3" title="This Element is only available for the premium version of xSlider" class="xbutton green"><i class="fa fa-youtube fa-lg"></i>&nbsp;&nbsp;Youtube</button>
                     <div class="space_between_less"></div>
                     <button type="button" style="width:130px; opacity:0.3" title="This Element is only available for the premium version of xSlider" class="xbutton green"><i class="fa fa-vimeo-square fa-lg"></i>&nbsp;&nbsp;Vimeo</button>
                     <br>
                     <br>
                     <button type="button" style="width:130px; opacity:0.3" title="This Element is only available for the premium version of xSlider" class="xbutton green"><i class="fa fa-video-camera fa-lg"></i>&nbsp;&nbsp;Html5 Video</button>
                     <div class="space_between_less"></div>
                     <button type="button" style="width:130px; opacity:0.3" title="This Element is only available for the premium version of xSlider" class="xbutton green"><i class="fa fa-html5 fa-lg"></i>&nbsp;&nbsp;Custom</button>
                  </div>

               <div>
                  <div style="display:inline-block; font-size:18px;" ng-click="togglePanel(project.ui.projectPanels, 'elements')">
                     Elements &nbsp;<i class="fa fa-lg" ng-class="project.ui.projectPanels.elements ? 'fa-angle-down' : 'fa-angle-right'"></i>
                  </div>
                  <div class="space_between"></div>
                  <div style="display:inline-block; float:right">
                     <button type="button" class="xbutton green" ng-mouseenter="elementButtons = true" ng-click="addElement('image')"><i class="fa fa-plus fa-lg"></i>&nbsp;&nbsp;Add Element</button>
                  </div>
               </div>

               <div class="" ng-show="currentSlide.elements.length == 0 && project.ui.projectPanels.elements" style="margin-top:20px;">
                  <div style="height:60px; padding: 40px 0 0 0; text-align:center; font-size:14px;">
                     <div class="w100">
                        <i>You haven't added any element yet. Do it by clicking one of the above buttons</i>
                     </div>
                  </div>
               </div>

               <div class="" ng-show="currentSlide && currentSlide.elements.length > 0 && project.ui.projectPanels.elements" ui-sortable="sortableElementsOptions" ng-model="currentSlide.elements" style="border-top:1px dashed #e0e0e0; margin-top:20px;">
                  <div ng-repeat="element in currentSlide.elements" ng-click="selectElement(element, $index)" class="el_row" ng-class="currentElement == element ? 'curr' : ''">
                    <div class="" style="float:left; width:220px; display:inline-block; padding-right:10px; height:21px; margin-top:5px;">
                        <div style="">
                           <div style="float:left; width:160px; overflow:hidden; white-space:nowrap; cursor:pointer; color:#000; padding-left:5px">
                              <span class="step_dragger"><i class="fa fa-bars" style="cursor:pointer;"></i></span>
                              <span class="handle" style="margin-left:2px;"><i class="fa" ng-class="getEye(element)" title="Show/Hide Element" ng-click="toggleElementVisibility(element)" ng-style="getEyeStyle(element)"></i></span>
                              <span class="handle" style="margin-left:2px;"><i class="fa" ng-class="getLock(element)" title="Lock/Unlock Element" ng-click="toggleElementLock(element)" ng-style="getLockStyle(element)"></i></span>
                              &nbsp;&nbsp;
                              {{element.name}}
                           </div>
                           <div style="float:right; color:#000">
                              <i class="fa fa-files-o" style="color:#aaa; cursor:pointer; margin-left:2px;" title="The duplication feature is only available for the premium version of xSlider"></i>
                              <i class="fa fa-upload" style="color:#aaa; cursor:pointer; margin-left:2px;" title="Copying elements between slides is only available for the premium version of xSlider" title="Copy Element to another Slide"></i>
                              <i class="fa fa-trash-o" style="cursor:pointer; margin-left:2px;" ng-click="deleteElement($index)" title="Delete Element"></i>
                           </div>
                        </div>
                     </div>
                     <div class="" style="margin-left:230px; margin-top: 2px; border-left:1px dashed #f0f0f0; padding:0px 0 0 10px">
                        <div range-slider min="0" max="1" decimal-places="4" model-min="element.start" model-max="element.end"></div>
                     </div>
                  </div>
               </div>

            </div>

            <div class="boxx ng_anim" style="margin-top:20px;" ng-show="currentElement && !currentElement.locked">
               <div class="" ng-show="currentElement">
                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'element')">
                        Element
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.element ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.element" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           Name <input type="text" ng-model="currentElement.name" style="width:150px">
                           <div class="space_between"></div>
                           Start <input type="text" ng-value="getStartValue()" style="width:60px" disabled> <span class="unit">sec</span>
                           <div class="space_between"></div>
                           End <input type="text" ng-value="getEndValue()" style="width:60px" disabled> <span class="unit">sec</span>
                           <div class="space_between"></div>
                           Duration <input type="text" ng-value="getDurationValue()" style="width:60px" disabled> <span class="unit">sec</span>
                        </div>
                        <div class="xrow">
                           Left <input type="text" ng-model="currentElement.left" style="width:60px"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Top <input type="text" ng-model="currentElement.top" style="width:60px"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           <button type="button" class="xbutton green" ng-click="centerElement('y')"><i class="fa fa-arrows-v fa-lg"></i>&nbsp;&nbsp;Center Vertically</button>
                           <div class="space_between_less"></div>
                           <button type="button" class="xbutton green" ng-click="centerElement('x')"><i class="fa fa-arrows-h fa-lg"></i>&nbsp;&nbsp;Center Horizontally</button>
                        </div>
                        <div class="xrow">
                           Class Name <input type="text" ng-model="currentElement.className" style="width:150px">
                           <i class="fa fa-info-circle fa-lg" tooltip title="Add a custom class name to the element"></i>
                        </div>
                     </div>

                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'text')" ng-show="currentElement.type == 'text'">
                        Text
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.text ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.text && currentElement.type == 'text'" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           Interpret the following text as
                           <select ng-model="currentElement.text.contentType">
                              <option value="plain">Plain Text</option>
                              <option value="html">Html</option>
                           </select>
                           <div style="display:inline-block" ng-if="currentElement.text.contentType == 'html'">
                              <div class="space_between"></div>
                              Convert 'new line' to <strong>&lt;br&gt;</strong>
                              <select ng-model="currentElement.text.nl2br" ng-options="f.value as f.label for f in selectValues.booleans">
                              </select>
                           </div>
                           <div style="display:inline-block" ng-if="currentElement.text.contentType == 'plain'">
                              <div class="space_between"></div>
                              White Space
                              <i class="fa fa-info-circle fa-lg" tooltip title="'normal': sequences of whitespace will collapse into a single whitespace. Text will wrap when necessary. 'nowrap': sequences of whitespace will collapse into a single whitespace. Text will never wrap to the next line. The text continues on the same line until a &lt;br&gt; tag is encountered. 'pre': whitespace is preserved by the browser. Text will only wrap on line breaks. Acts like the &lt;pre&gt; tag in HTML. 'pre-line': sequences of whitespace will collapse into a single whitespace. Text will wrap when necessary and on line breaks. 'pre-wrap': whitespace is preserved by the browser. Text will wrap when necessary and on line breaks."></i>
                              <select ng-model="currentElement.text.whiteSpace">
                                 <option value="normal">Normal</option>
                                 <option value="nowrap">No Wrap</option>
                                 <option value="pre">Pre</option>
                                 <option value="pre-line">Pre Line</option>
                                 <option value="pre-wrap">Pre Wrap</option>
                              </select>
                           </div>
                           <br>
                           <textarea id="txt_textarea" style="width:100%; margin-top:10px; height:70px;" ng-model="currentElement.text.content"></textarea>
                        </div>
                        <div class="xrow">
                           <!-- <button type="button" class="xbutton blue" ng-click="openAwesomePopup('add')"><i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;Add Font Icon</button>
                           <div class="space_between"></div> -->
                           <span class="align_box" ng-click="setAlignment(currentElement.text, 'left')" ng-class="{'current': currentElement.text.textAlign == 'left'}">
                              <i class="fa fa-align-left" style="font-size:14px;"></i>
                           </span>
                           <span class="align_box" ng-click="setAlignment(currentElement.text, 'center')" ng-class="{'current': currentElement.text.textAlign == 'center'}">
                              <i class="fa fa-align-center" style="font-size:14px;"></i>
                           </span>
                           <span class="align_box" ng-click="setAlignment(currentElement.text, 'right')" ng-class="{'current': currentElement.text.textAlign == 'right'}">
                              <i class="fa fa-align-right" style="font-size:14px;"></i>
                           </span>
                           <div class="space_between"></div>
                           Font
                           <select ng-model="currentElement.text.fontFamilyName" ng-change="onFontFamilySelected(currentElement.text)" ng-options="f.name for f in fonts" style="width:180px">
                              <option value="">--- select font ---</option>
                           </select>
                           <input type="text" ng-model="currentElement.text.fontFamily" style="width:200px;">
                        </div>
                        <div class="xrow">
                           Size
                           <islider min="6" max="100" step="1" ng-model="currentElement.text.fontSize" ng-slider-change="" style="width:90px!important"></islider>
                           <input type="text" ng-model="currentElement.text.fontSize" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Line-height
                           <islider min="6" max="100" step="1" ng-model="currentElement.text.lineHeight" ng-slider-change="" style="width:90px!important"></islider>
                           <input type="text" ng-model="currentElement.text.lineHeight" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Spacing
                           <islider min="-3" max="20" step="0.5" ng-model="currentElement.text.letterSpacing" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.letterSpacing" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                        </div>
                        <div class="xrow">
                           Weight
                           <select ng-model="currentElement.text.fontWeight">
                              <option value="100">100</option>
                              <option value="200">200</option>
                              <option value="300">300</option>
                              <option value="400">400 (normal)</option>
                              <option value="500">500</option>
                              <option value="600">600</option>
                              <option value="700">700 (bold)</option>
                              <option value="800">800</option>
                              <option value="900">900</option>
                           </select>
                           <div class="space_between"></div>
                           Style
                           <select ng-model="currentElement.text.fontStyle">
                              <option value="normal">normal</option>
                              <option value="italic">italic</option>
                              <option value="oblique">oblique</option>
                           </select>
                           <div class="space_between"></div>
                           Color <spectrum-colorpicker
                             ng-model="currentElement.text.color"
                             format="hex"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:false, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                           <div class="space_between"></div>
                           Decoration
                           <select ng-model="currentElement.text.textDecoration">
                              <option value="none">none</option>
                              <option value="underline">underline</option>
                              <option value="overline">overline</option>
                              <option value="line-through">line-through</option>
                           </select>
                        </div>
                        <div class="xrow">
                           Tag
                           <select ng-model="currentElement.text.tag">
                              <option value="div">div</option>
                              <option value="p">p</option>
                              <option value="h1">h1</option>
                              <option value="h2">h2</option>
                              <option value="h3">h3</option>
                              <option value="h4">h4</option>
                              <option value="h5">h5</option>
                              <option value="h6">h6</option>
                           </select>
                           <div class="space_between"></div>
                           Opacity
                           <islider min="0" max="1.00001" step="0.1" ng-model="currentElement.text.opacity" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.opacity" min="0" max="1" style="width:40px; margin-left:10px;">
                           <div class="space_between"></div>
                        </div>
                        <div class="xrow">
                           Rotation
                           <div class="space_between_less"></div>
                           X
                           <islider min="-180" max="180" step="5" ng-model="currentElement.text.rotationX" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.rotationX" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Y
                           <islider min="-180" max="180" step="5" ng-model="currentElement.text.rotationY" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.rotationY" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Z
                           <islider min="-180" max="180" step="5" ng-model="currentElement.text.rotationZ" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.rotationZ" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                        </div>
                        <div class="xrow">
                           Shadow
                           <div class="space_between_less"></div>
                           H
                           <islider min="-20" max="20" step="1" ng-model="currentElement.text.shadowH" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.shadowH" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           V
                           <islider min="-20" max="20" step="1" ng-model="currentElement.text.shadowV" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.shadowV" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Blur
                           <islider min="0" max="40" step="1" ng-model="currentElement.text.shadowBlur" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.text.shadowBlur" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           <spectrum-colorpicker
                             ng-model="currentElement.text.shadowColor"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                        </div>

                     </div>



                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'fontIcon')" ng-show="currentElement.type == 'fontIcon'">
                        Font Icon
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.fontIcon ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.fontIcon && currentElement.type == 'fontIcon'" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           <button type="button" class="xbutton blue" ng-click="openAwesomePopup('change')"><i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;Change Icon</button>
                           <div class="space_between"></div>
                           Color <spectrum-colorpicker
                             ng-model="currentElement.fontIcon.color"
                             format="hex"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:false, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                           <div class="space_between"></div>
                           Transform
                           <select ng-model="currentElement.fontIcon.transform">
                              <option value="">- none -</option>
                              <option value="fa-spin">Spin Animation</option>
                              <option value="fa-pulse">Pulse Animation (8 steps rotation)</option>
                              <option value="fa-rotate-90">Rotate 90°</option>
                              <option value="fa-rotate-180">Rotate 180°</option>
                              <option value="fa-rotate-270">Rotate 270°</option>
                              <option value="fa-flip-horizontal">Flip Horizontal</option>
                              <option value="fa-flip-vertical">Flip Vertical</option>
                           </select>
                        </div>
                        <div class="xrow">
                           Size
                           <islider min="6" max="200" step="1" ng-model="currentElement.fontIcon.fontSize" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.fontSize" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Line-height
                           <islider min="6" max="200" step="1" ng-model="currentElement.fontIcon.lineHeight" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.lineHeight" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Opacity
                           <islider min="0" max="1.00001" step="0.1" ng-model="currentElement.fontIcon.opacity" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.opacity" min="0" max="1.00001" style="width:40px; margin-left:10px;">
                        </div>
                        <div class="xrow">
                           Rotation
                           <div class="space_between_less"></div>
                           X
                           <islider min="-180" max="180" step="5" ng-model="currentElement.fontIcon.rotationX" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.rotationX" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Y
                           <islider min="-180" max="180" step="5" ng-model="currentElement.fontIcon.rotationY" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.rotationY" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Z
                           <islider min="-180" max="180" step="5" ng-model="currentElement.fontIcon.rotationZ" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.rotationZ" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                        </div>
                        <div class="xrow">
                           Shadow
                           <div class="space_between_less"></div>
                           H
                           <islider min="-20" max="20" step="1" ng-model="currentElement.fontIcon.shadowH" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.shadowH" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           V
                           <islider min="-20" max="20" step="1" ng-model="currentElement.fontIcon.shadowV" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.shadowV" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Blur
                           <islider min="0" max="40" step="1" ng-model="currentElement.fontIcon.shadowBlur" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.fontIcon.shadowBlur" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           <spectrum-colorpicker
                             ng-model="currentElement.fontIcon.shadowColor"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                        </div>
                     </div>



                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'image')" ng-show="currentElement.type == 'image'">
                        Image
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.image ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.image && currentElement.type == 'image'" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           <button type="button" class="xbutton blue" ng-click="changeImage()"><i class="fa fa-flag fa-lg"></i>&nbsp;&nbsp;Change Image</button>
                           <div class="space_between"></div>
                           Alt Attribute
                           <input type="text" ng-model="currentElement.image.altTag" style="width:454px; margin-left:10px;">
                        </div>
                        <div class="xrow">
                           Preserve Aspect Ratio
                           <select ng-model="currentElement.image.aspectRatio" ng-change="onImageAspectRatioChange()" ng-options="f.value as f.label for f in selectValues.booleans">
                           </select>
                           <div class="space_between"></div>
                           Width
                           <input type="text" ng-model="currentElement.image.width" ng-change="onImageWidthChange()" style="width:50px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Height
                           <input type="text" ng-model="currentElement.image.height" ng-change="onImageHeightChange()" style="width:50px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Opacity
                           <islider min="0" max="1.00001" step="0.1" ng-model="currentElement.image.opacity" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.opacity" min="0" max="1" style="width:50px; margin-left:10px;">
                        </div>
                        <div class="xrow">
                           Rotation
                           <div class="space_between_less"></div>
                           X
                           <islider min="-180" max="180" step="5" ng-model="currentElement.image.rotationX" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.rotationX" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Y
                           <islider min="-180" max="180" step="5" ng-model="currentElement.image.rotationY" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.rotationY" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Z
                           <islider min="-180" max="180" step="5" ng-model="currentElement.image.rotationZ" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.rotationZ" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                        </div>
                        <div class="xrow">
                           Shadow
                           <div class="space_between_less"></div>
                           H
                           <islider min="-20" max="20" step="1" ng-model="currentElement.image.shadowH" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.shadowH" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           V
                           <islider min="-20" max="20" step="1" ng-model="currentElement.image.shadowV" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.shadowV" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Blur
                           <islider min="0" max="40" step="1" ng-model="currentElement.image.shadowBlur" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.image.shadowBlur" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           <spectrum-colorpicker
                             ng-model="currentElement.image.shadowColor"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                        </div>
                     </div>





                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'empty')" ng-show="currentElement.type == 'empty'">
                        Dimensions
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.empty ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.empty && currentElement.type == 'empty'" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           Width
                           <input type="text" ng-model="currentElement.empty.width" ng-change="onEmptyWidthChange()" style="width:50px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Height
                           <input type="text" ng-model="currentElement.empty.height" ng-change="onEmptyHeightChange()" style="width:50px; margin-left:10px;"> <span class="unit">px</span>
                        </div>
                     </div>



                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'container')">
                        Container
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.container ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.container" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           Background
                           <select ng-model="currentElement.container.bgType">
                              <option value="image">Image</option>
                              <option value="external">External URL</option>
                              <option value="color">Solid Color</option>
                              <option value="none">Transparent</option>
                           </select>
                           <div style="display:inline-block" ng-show="currentElement.container.bgType == 'image'">
                              <div class="space_between"></div>
                              Image
                              <button type="button" class="xbutton blue" ng-click="elementBgSelectImage()"><i class="fa fa-image fa-lg"></i><div class="space_between"></div>Select Image</button>
                           </div>
                           <div style="display:inline-block" ng-show="currentElement.container.bgType == 'external'">
                              <div class="space_between"></div>
                              Url
                              <input type="text" ng-model="currentElement.container.bgUrl" style="width:340px">
                           </div>
                           <div style="display:inline-block" ng-show="currentElement.container.bgType != 'none'">
                              <div class="space_between"></div>
                              Color
                              <spectrum-colorpicker ng-model="currentElement.container.bgColor" options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:true, preferredFormat:'hex'}"></spectrum-colorpicker>
                           </div>
                        </div>
                        <div class="xrow" ng-show="currentElement.container.bgType == 'image' || currentElement.container.bgType == 'external'">
                           Background Fit
                           <select ng-model="currentElement.container.bgFit">
                              <option value="">None</option>
                              <option value="contain">Contain</option>
                              <option value="cover">Cover</option>
                           </select>
                           <div class="space_between"></div>Repeat
                           <select ng-model="currentElement.container.bgRepeat">
                              <option value="no-repeat">no-repeat</option>
                              <option value="repeat">repeat</option>
                              <option value="repeat-x">repeat-x</option>
                              <option value="repeat-y">repeat-y</option>
                           </select>
                           <div class="space_between"></div>Position
                           <select ng-model="currentElement.container.bgPosition">
                              <option value="center top">center top</option>
                              <option value="center center">center center</option>
                              <option value="center bottom">center bottom</option>
                              <option value="left top">left top</option>
                              <option value="left center">left center</option>
                              <option value="left bottom">left bottom</option>
                              <option value="right top">right top</option>
                              <option value="right center">right center</option>
                              <option value="right bottom">right bottom</option>
                           </select>
                        </div>
                        <div class="xrow">
                           Overflow
                           <select ng-model="currentElement.container.overflow">
                              <option value="hidden">hidden</option>
                              <option value="visible">visible</option>
                           </select>
                           <div class="space_between"></div>
                           Padding &nbsp;
                           <i class="fa fa-arrow-up"></i><input type="text" ng-model="currentElement.container.paddingTop" style="width:40px;">
                           <i class="fa fa-arrow-right"></i><input type="text" ng-model="currentElement.container.paddingRight" style="width:40px;">
                           <i class="fa fa-arrow-down"></i><input type="text" ng-model="currentElement.container.paddingBottom" style="width:40px;">
                           <i class="fa fa-arrow-left"></i><input type="text" ng-model="currentElement.container.paddingLeft" style="width:40px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Opacity
                           <islider min="0" max="1.00001" step="0.1" ng-model="currentElement.container.opacity" ng-slider-change="" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.opacity" min="0" max="1" style="width:50px; margin-left:10px;">
                        </div>
                        <div class="xrow">
                           Border
                           <spectrum-colorpicker
                             ng-model="currentElement.container.borderColor"
                             format="hex"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:false, allowEmpty:true, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                           <div class="space_between"></div>
                           Size
                           <islider min="0" max="10" step="1" ng-model="currentElement.container.borderWidth" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.borderWidth" style="width:40px; margin-left:10px;">
                           <div class="space_between"></div>
                           Style
                           <select ng-model="currentElement.container.borderStyle">
                              <option value="solid">solid</option>
                              <option value="dotted">dotted</option>
                              <option value="dashed">dashed</option>
                              <option value="double">double</option>
                              <option value="groove">groove</option>
                              <option value="ridge">ridge</option>
                              <option value="inset">inset</option>
                              <option value="outset">outset</option>
                           </select>
                           <div class="space_between"></div>
                           Radius
                           <input type="text" ng-model="currentElement.container.borderTopLeftRadius" style="width:40px;">
                           <input type="text" ng-model="currentElement.container.borderTopRightRadius" style="width:40px;">
                           <input type="text" ng-model="currentElement.container.borderBottomRightRadius" style="width:40px;">
                           <input type="text" ng-model="currentElement.container.borderBottomLeftRadius" style="width:40px;"> <span class="unit">px</span>
                        </div>
                        <div class="xrow">
                           Rotation
                           <div class="space_between_less"></div>
                           X
                           <islider min="-180" max="180" step="5" ng-model="currentElement.container.rotationX" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.rotationX" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Y
                           <islider min="-180" max="180" step="5" ng-model="currentElement.container.rotationY" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.rotationY" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                           <div class="space_between"></div>
                           Z
                           <islider min="-180" max="180" step="5" ng-model="currentElement.container.rotationZ" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.rotationZ" style="width:40px; margin-left:10px;"> <span class="unit">°</span>
                        </div>
                        <div class="xrow">
                           Shadow
                           <div class="space_between_less"></div>
                           H
                           <islider min="-20" max="20" step="1" ng-model="currentElement.container.shadowH" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.shadowH" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           V
                           <islider min="-20" max="20" step="1" ng-model="currentElement.container.shadowV" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.shadowV" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           Blur
                           <islider min="0" max="40" step="1" ng-model="currentElement.container.shadowBlur" style="width:70px!important"></islider>
                           <input type="text" ng-model="currentElement.container.shadowBlur" style="width:40px; margin-left:10px;"> <span class="unit">px</span>
                           <div class="space_between"></div>
                           <spectrum-colorpicker
                             ng-model="currentElement.container.shadowColor"
                             options="{clickoutFiresChange:true, showButtons:false, showInput:true, showAlpha:true, allowEmpty:false, preferredFormat:'hex'}">
                           </spectrum-colorpicker>
                        </div>
                     </div>


                     <h4 class="panel_head" ng-click="togglePanel(project.ui.elementPanels, 'animations')">
                        Animations
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.animations ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.animations" style="padding:10px 0px 10px 3px">

                        <div class="xrow first" style="border-bottom:1px solid #eee; margin-bottom:20px; padding-bottom:20px">
                           <strong style="font-size:16px">Loop Effects</strong><br>
                           <i>Loop effects are only available for the premium version of xSlider</i>
                           <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                        </div>


                        <table width="100%" cellspacing="0">
                           <tr>
                              <td>

                              </td>
                              <td style="white-space:nowrap; padding:0">
                                 <div style="float:left"><strong class="unit">0 sec</strong></div>
                                 <div style="float:right"><strong class="unit">{{getElementTime(currentSlide, currentElement)}} sec</strong></div>
                                 <div class="unit" style="width:100%; text-align:center;">element time on screen</div>
                              </td>
                              <td width="1"></td>
                           </tr>
                           <tr>
                              <td width="1" nowrap="nowrap" style="padding-right:10px; white-space:nowrap">
                                 <button type="button" class="xbutton blue" ng-click="previewEffect(0)"><i class="fa fa-play"></i></button>
                                 <select ng-model="currentElement.animations[0].effect" ng-change="previewEffect(0)" ng-options="item.key as item.name for item in effectMap.enter" style="width:150px">
                                 </select>
                                 <input ng-if="currentElement.type == 'text' && currentElement.text.split != ''" type="text" ng-model="currentElement.animations[0].splitDelay" style="width:40px;" tooltip title="Here you can specify the delay between letters/words/lines. Insert a value between 0 (all items will be animated together) and 100 (the maximum delay, i.e. the animation time is 0). Choosing values near 50 should grant better results.">
                              </td>
                              <td style="white-space:nowrap; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom: 1px dashed #e0e0e0; border-top: 1px dashed #e0e0e0; background:#f6f6f6">
                                 <div range-slider min="0" max="1" decimal-places="4" model-min="currentElement.animations[0].start" model-max="currentElement.animations[0].end" pin-handle="min" ng-mouseup="previewEffect(0)"></div>
                              </td>
                              <td></td>
                           </tr>
                           <tr ng-repeat="animation in currentElement.animations | filter:{type:'cue'}">
                              <td width="1" nowrap="nowrap" style="padding-right:10px; white-space:nowrap">
                                 <button type="button" class="xbutton blue" ng-click="previewEffect($index+2)"><i class="fa fa-play"></i></button>
                                 <select ng-model="animation.effect" ng-change="previewEffect($index+2)" ng-options="item.key as item.name for item in effectMap.cue" style="width:150px">
                                 </select>
                                 <input ng-if="currentElement.type == 'text' && currentElement.text.split != '' && animation.effect.indexOf('move') == -1" type="text" ng-model="animation.splitDelay" style="width:40px;" tooltip title="Here you can specify a value between 0 and 100 for the delay between letters/words/lines. The greater the value the longer the delay.">
                                 <button ng-if="animation.effect.indexOf('move') > -1" type="button" class="xbutton" ng-class="{'blue': currentElement.moveIdx == $index+2}" ng-click="showMoveElement($index+2)" tooltip title="A new temporary element will be shown inside the editor allowing you to set the position to reach. Drag it to end position."><i class="fa fa-crosshairs"></i></button>
                              </td>
                              <td style="white-space:nowrap; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom: 1px dashed #e0e0e0; background:#f6f6f6">
                                 <div range-slider min="0" max="1" decimal-places="4" model-min="animation.start" model-max="animation.end" ng-mouseup="previewEffect($index+2)"></div>
                              </td>
                              <td nowrap="nowrap">

                              </td>
                           </tr>
                           <tr>
                              <td width="1" nowrap="nowrap" style="padding-right:10px; white-space:nowrap">
                                 <button type="button" class="xbutton blue" ng-click="previewEffect(1)"><i class="fa fa-play"></i></button>
                                 <select ng-model="currentElement.animations[1].effect" ng-change="previewEffect(1)" ng-options="item.key as item.name for item in effectMap.exit" style="width:150px">
                                 </select>
                                 <input ng-if="currentElement.type == 'text' && currentElement.text.split != ''" type="text" ng-model="currentElement.animations[1].splitDelay" style="width:40px;" tooltip title="Here you can specify the delay between letters/words in seconds (use the dot and not the coma)">
                              </td>
                              <td style="white-space:nowrap; border-left:1px solid #ccc; border-right:1px solid #ccc; border-bottom: 1px dashed #e0e0e0; background:#f6f6f6">
                                 <div range-slider min="0" max="1" decimal-places="4" model-min="currentElement.animations[1].start" model-max="currentElement.animations[1].end" pin-handle="max" ng-mouseup="previewEffect(1)"></div>
                              </td>
                              <td></td>
                           </tr>
                           <tr>
                              <td style="white-space:nowrap; padding:5px 0 0 36px">
                                 <button type="button" class="xbutton green" style="opacity:0.5" tooltip title="This feature is only available for the premium version of xSlider" style="width:140px; text-align:center"><i class="fa fa-plus"></i> Add Effect</button>
                              </td>
                              <td style="white-space:nowrap; padding:5px 0 0 0">

                              </td>
                              <td width="1"></td>
                           </tr>
                        </table>

                        <div class="element_fx_container">
                           <div id="elementAnimPreview" ng-click="previewFx()" style="width:200px; height: 100px; line-height:100px; background:#000; color:#fff; text-align:center; margin: 40px auto; overflow: hidden;">
                              EFFECT PREVIEW
                           </div>
                        </div>


                     </div>





                     <h4 class="panel_head" style="color:#aaa" ng-click="togglePanel(project.ui.elementPanels, 'parallax')">
                        Parallax
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.parallax ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.parallax" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           <i>Parallax is only available for the premium version</i><br><br>
                           <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                        </div>
                     </div>


                     <h4 class="panel_head" style="color:#aaa" ng-click="togglePanel(project.ui.elementPanels, 'interactivity')">
                        Interactivity
                        <div style="float:right; display:inline-block">
                           <i class="fa fa-lg" ng-class="project.ui.elementPanels.interactivity ? 'fa-angle-down' : 'fa-angle-right'"></i>
                        </div>
                     </h4>
                     <div ng-show="project.ui.elementPanels.interactivity" style="padding:10px 10px 10px 10px">
                        <div class="xrow first">
                           <i>Interactivity is only available for the premium version</i><br><br>
                           <button type="button" class="xbutton orange" onclick="window.open('http://xslider.develvet.com?ref=xslider-wp-free')"><i class="fa fa-external-link fa-lg"></i>&nbsp;&nbsp;GET XSLIDER NOW..</button>
                        </div>
                     </div>

               </div>
            </div>

         </div>

      </div>
   </div>






   <div id="overlay" ng-click="closePopup(currentPopupCloseCallback)">
   </div>

   <div id="popup_preview" class="popup" style="overflow:scroll">
      <div style="padding:20px; text-align:center">
         <button class="xbutton gray" ng-click="closePreview()" style="margin-top:10px;"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE PREVIEW</button>
         <div id="preview_container"></div>
      </div>
   </div>

   <div id="popup_awesome" class="popup">
      <div style="padding:20px;">
         <div style="float:left; width:98%; margin:0 auto 20px auto; padding:10px 10px 10px 10px; border-bottom:1px solid #e0e0e0; text-align:left">
            <div style="float:left"><h1>Select the icon to be added</h1></div>
            <div style="float:right">
               Search by keyword: <input type="text" ng-model="aweSearch" style="width:200px">
            </div>
         </div>
         <div class="x_awe_icon" ng-repeat="a in awesomeIcons | filter:aweSearch" ng-click="selectAwesome(a)" tooltip title="{{a}}">
            <i class="fa {{a}}"></i>
         </div>
         <div style="float:left; clear:both; width:100%; text-align:center; padding:20px 0; margin-top:10px; border-top:1px solid #e0e0e0">
            <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
         </div>
      </div>
   </div>

   <div id="popup_css" class="popup">
      <div style="padding:20px;">
         <div style="border-bottom:1px solid #ccc">
            <h1>Css Classes</h1>
         </div>
         <div style="margin-top:20px;">
            <div style="padding-bottom:10px;">
               Automatically add the <strong style="color:#CC6666">!important</strong> declaration <i class="fa fa-info-circle fa-lg" tooltip title="If you want that your css classes override the UI settings (that are added as inline style), you have to add the !important declaration to each css line. This option does that for you."></i>
               <select ng-model="project.cssImportant" ng-options="f.value as f.label for f in selectValues.booleans">
               </select>
            </div>
            <div id="popup_css_ace" style="height:800px; border:1px solid #ddd" ui-ace="{mode:'css', theme:'tomorrow_night', showPrintMargin:false, showIndentGuides:true, lockFirstAndLastRow:false}" ng-model="project.css"></div>
         </div>
         <div style="padding:20px 0 0 0; text-align:center">
            <button class="xbutton green" ng-click="closePopup()"><i class="fa fa-save fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
         </div>
      </div>
   </div>

   <div id="popup_confirm" class="popup">
      <div style="padding:20px; text-align:center">
         <span ng-bind-html="popup_confirm_text"></span>
         <button class="xbutton blue" ng-click="popup_confirm_do()"><i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;OK</button>
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
      </div>
   </div>

   <div id="popup_message" class="popup">
      <div style="padding:20px; text-align:center">
         <span ng-bind-html="popup_message_text"></span>
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
      </div>
   </div>

   <div id="popup_input" class="popup">
      <div style="padding:20px; text-align:center">
         <span ng-bind-html="popup_text"></span>
         <input type="text" ng-model="popup_input" style="width:{{popup_input_width}};">
         <br><br>
         <button class="xbutton blue" ng-click="popup_confirm_do()"><i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;OK</button>
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
      </div>
   </div>

   <div id="popup_slide_duration" class="popup">
      <div style="padding:20px; text-align:center">
         <h2>Change Slide Duration</h2>
         <br>
         <span>Enter the new slide duration (the minimum value is <strong>{{getMinDuration()}} seconds</strong>)</span>
         <input type="text" ng-model="newDuration" style="width:50px;"> <span class="unit">sec</span>
         <br><br>
         <button class="xbutton blue" ng-if="newDuration >= getMinDuration()" ng-click="doChangeSlideDuration()"><i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;OK</button>
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
      </div>
   </div>

   <div id="popup_copy_to" class="popup">
      <div style="padding:20px; text-align:center">
         <h2 style="padding:20px 0">Please choose a destination slider?</h2>
         <select ng-model="copyTo.slider" ng-options="f.id as copyToGetSliderName(f) for f in copyTo.sliders">
            <option value="">- select a slider -</option>
         </select>
         <br><br><br>
         <button ng-if="copyTo.slider" class="xbutton green" ng-click="doCopyTo()"><i class="fa fa-sign-in fa-lg"></i>&nbsp;&nbsp;COPY</button>
         &nbsp;
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
         <div style="margin-top:30px; padding-bottom:20px" ng-if="copyTo.done">
            <i class="fa fa-thumbs-o-up fa-lg" style="font-size:28px; color:#FDC400"></i>&nbsp;&nbsp;your content has been copied!
         </div>
      </div>
   </div>

   <div id="popup_copy_element" class="popup">
      <div style="padding:20px; text-align:center">
         <h2 style="padding:20px 0">Please choose a destination slide?</h2>
         <select ng-model="copyElement.slide" ng-options="idx as item.name for (idx, item) in project.slides">
            <option value="">- select a slide -</option>
         </select>
         <br><br><br>
         <button ng-show="copyElement.slide" class="xbutton green" ng-click="doCopyElement()"><i class="fa fa-sign-in fa-lg"></i>&nbsp;&nbsp;COPY</button>
         &nbsp;
         <button class="xbutton gray" ng-click="closePopup()"><i class="fa fa-times fa-lg"></i>&nbsp;&nbsp;CLOSE</button>
         <div style="margin-top:30px; padding-bottom:20px" ng-if="copyElement.done">
            <i class="fa fa-thumbs-o-up fa-lg" style="font-size:28px; color:#FDC400"></i>&nbsp;&nbsp;the element has been copied!
         </div>
      </div>
   </div>


<div id="sticky_buttons">
   <button type="button" class="xbutton green" style="width:110px; text-align:left" ng-click="saveProject()"><i class="fa fa-save" style="width:14px;"></i>&nbsp;&nbsp;SAVE</button>
   <br>
   <button type="button" class="xbutton blue" style="width:110px; margin-top:4px; text-align:left" ng-click="preview(currentSlideIdx, true)"><i class="fa fa-play" style="width:14px;"></i>&nbsp;&nbsp;PLAY SLIDE</button>
   <br>
   <button type="button" class="xbutton blue" style="width:110px; margin-top:4px; text-align:left" ng-click="preview(0, false)"><i class="fa fa-play" style="width:14px;"></i>&nbsp;&nbsp;PLAY ALL</button>
</div>

<div id="sticky_msg">
   <i class="fa fa-thumbs-o-up fa-lg" style="font-size:28px; color:#FDC400"></i>&nbsp;&nbsp;saved!
</div>

</form>
</div>
</div>
