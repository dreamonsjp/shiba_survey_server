<?php 
	$ShowAllQuestion = 1;
	$ShowDetailQuestion = 1;
	$idcategory = $_GET['id'];
	$domain = "http://myphamteen.com/esurvey/";
	$Getcategory = file_get_contents('http://question_bank.dreamons.jp/categories/json_list/?parent_category=1');
	//$Showcategory = file_get_contents('http://question_bank.dreamons.jp/questions/json_list?category=2');
	$category = json_decode($Getcategory, TRUE);

	for ($i=0; $i < count($category['categories']); $i++) { 
		/// show category
		?>
			<script type='text/javascript'>
				$(function(){		
				
					$('select.sortCategory').append($('<option class="optionCategory" value="<?php echo $category['categories'][$i]['id']; ?>">').html('<?php echo $category['categories'][$i]['name']; ?>'));
					
				}); 
				
			</script>
		
		
		<?php
		
		
		
		$GetShowcategory = file_get_contents('http://question_bank.dreamons.jp/questions/json_list?category='.$category['categories'][$i]['id'].'');

		$Showcategory = json_decode($GetShowcategory,true);
		//$CountQuestion = count($Showcategory['questions']);

		for ($q=0; $q < count($Showcategory['questions']); $q++) { 
			
			// 
			
				$getCode = $Showcategory['questions'][$q]['title']; 
				$idholder = $Showcategory['questions'][$q]['id']; 
				$codeDB = file_get_contents(''.$domain.'webservice/questionbank.php?code=1&survey_id='.$surveyid.'&title='.$getCode.'');
				
				if($codeDB != 1){
					
					$buttonAdd = '<a data-qid='.$Showcategory['questions'][$q]['id'].' class="holdertext'.$idholder.' btn-holder'.$idholder.' btn btn-small question-addBtn" >+ Add</a>';
					
					$inputcheckbox = '<input id='.$Showcategory['questions'][$q]['id'].' type="checkbox" class="checkboxQuestion checkboxoff'.$idholder.'" value='.$Showcategory['questions'][$q]['id'].'>';
					
				}else{
				
					$buttonAdd = "<b>Added</b>";
					
					$inputcheckbox = '<input id='.$Showcategory['questions'][$q]['id'].' type="checkbox" class="checkboxQuestion checkboxoff'.$idholder.'" value='.$Showcategory['questions'][$q]['id'].' disabled="">';
					
				}
			
				
			
				
				$Showcategory['questions'][$q]['title']; 
				$replace = $Showcategory['questions'][$q]['question']; 
				$holder = explode("%", $replace);
				$holderEdit = $holder[1];
				$holderBefore = $holder[0];
				$holderAfter = $holder[2];
				//echo $holderEdit;
				if(strlen($holderEdit) > 0){

					$holderClass = "replace-holder";
					$holderId = "";
					$echoHolderText = $holderBefore."<a class=".$holderClass.">".$holderEdit."</a>".$holderAfter;
					
				}else{
				
					$holderClass = "";
					$holderId = "";
					$echoHolderText = $replace;
				
				}
			
			//
			?>
				 <script type='text/javascript'>
					
					$(function(){		
						
						
						
						$('ul.questions').append($('<li class="question show <?php echo "tableftcategory".$Showcategory['questions'][$q]['category_id']; ?>" data-qid="<?php echo $Showcategory['questions'][$q]['id']; ?>">').html(' <div class="col input-col"> <?php echo $inputcheckbox; ?> </div> <div class="question-text col"> <span class="notranslate holder<?php echo $idholder; ?>"> <?php echo $echoHolderText ?> </span> <span class="show">View Answer Options</span> <div class="slidingDiv"> <a><?php echo str_replace("\n","</br>",$Showcategory['questions'][$q]['answer']) ?></a></br> </div> </div> </div> <div class="col button-col"> <em></em> <?php echo $buttonAdd; ?> </div>'));
						
						//alert($(".holder36").text());
						
					}); 
					
					// choice multi question
				
						function AddkMulti(){
							var idSelector = function() { return this.value; };
							var fruitsGranted = $(".checkboxQuestion:checkbox:checked").map(idSelector).get();
							$.ajax({url:"<?php echo $domain; ?>webservice/questionbank.php?multi=1&survey_id=<?php echo $surveyid; ?>&iGroupID=<?php echo $gid; ?>&qid=<?php echo $idholder; ?>&datamulti="+fruitsGranted,
								beforeSend: function() {
									$('.loader2').show();
								},
								success:function(result){
								$('.loader2').hide();
								console.log(result);
								var rtdata = result.substring(0, result.length - 1);
								//alert(rtdata);
								var myArray = result.split(',');
								for(var i=0;i<myArray.length;i++){
									$("a.btn-holder"+myArray[i]).replaceWith( "<b>Added</b>" );
									$(".checkboxoff"+myArray[i]).prop("disabled", true);
								}
								
							}});
						}
				</script>
				
			 <?php
				// add question bank
				if(strlen($holderEdit) > 0){
				
					?>
					
						 <script type='text/javascript'>
							
							$(document).ready(function(){
								  $(".btn-holder<?php echo $idholder; ?>").click(function(){
									//alert($(".holder<?php echo $idholder; ?>").text());
									var holder = $(".holder<?php echo $idholder; ?>").text();
									//$("a.btn-holder<?php echo $idholder; ?>").attr("href", "<?php echo $domain; ?>webservice/questionbank.php?survey_id=<?php echo $surveyid; ?>&iGroupID=<?php echo $gid; ?>&qid=<?php echo $idholder; ?>&holder="+holder);
										
										 $.ajax({url:"<?php echo $domain; ?>webservice/questionbank.php?import=1&survey_id=<?php echo $surveyid; ?>&iGroupID=<?php echo $gid; ?>&qid=<?php echo $idholder; ?>&holder="+holder,
											beforeSend: function() {
												$('.loader2').show();
											},
											success:function(result){
											$('.loader2').hide();
											console.log(result);
											var obj = jQuery.parseJSON(result);
											if(obj.code == 123){
												$("a.btn-holder<?php echo $idholder; ?>").replaceWith( "<b>Added</b>" );
												$(".checkboxoff<?php echo $idholder; ?>").prop("disabled", true);
											}
											
										}});
								  
								  });
							});
						</script>
					
					<?php
				
				}else{
				
					?>
					
						 <script type='text/javascript'>
							
							$(document).ready(function(){
								  $(".btn-holder<?php echo $idholder; ?>").click(function(){
									$.ajax({url:"<?php echo $domain; ?>webservice/questionbank.php?import=1&survey_id=<?php echo $surveyid; ?>&iGroupID=<?php echo $gid; ?>&qid=<?php echo $idholder; ?>&holder",
										beforeSend: function() {
											$('.loader2').show();
										},
										success:function(result){
										$('.loader2').hide();
										console.log(result);
										var obj = jQuery.parseJSON(result);
											//alert( obj.code);
											if(obj.code == 123){
												$("a.btn-holder<?php echo $idholder; ?>").replaceWith( "<b>Added</b>" );
												$(".checkboxoff<?php echo $idholder; ?>").prop("disabled", true);
											}
									}});
								  });
							});
						</script>
					
					<?php
				
				}
			
		}			
	}
?>

<script type='text/javascript'>
    var attr_url = "<?php echo $this->createUrl('admin/questions', array('sa' => 'ajaxquestionattributes')); ?>";
    var imgurl = '<?php echo Yii::app()->getConfig('imageurl'); ?>';
    var yii_csrf = "<?php echo Yii::app()->request->csrfToken; ?>";
    
</script>


<!-- adding js -->
<script type='text/javascript' src="http://vitalets.github.com/x-editable/assets/poshytip/jquery.poshytip.js"></script>
<script type='text/javascript' src="http://myphamteen.com/esurvey/assets/js/jquery.removeDiacritics.js"></script>
<link rel="stylesheet" type="text/css" href="http://vitalets.github.com/x-editable/assets/poshytip/tip-yellowsimple/tip-yellowsimple.css">
<script type='text/javascript' src="http://vitalets.github.com/x-editable/assets/mockjax/jquery.mockjax.js"></script>
<script type='text/javascript' src="http://vitalets.github.com/x-editable/assets/x-editable/jquery-editable/js/jquery-editable-poshytip.js"></script>  
<link rel="stylesheet" type="text/css" href="http://vitalets.github.com/x-editable/assets/x-editable/jquery-editable/css/jquery-editable.css">


<script type='text/javascript'>//<![CDATA[ 
jQuery(window).load(function(){
jQuery(function(){ // DOM READY shorthand
// function scrollTo($element){
	// jQuery('html,body').animate({scrollTop: $element.offset().top},'slow');
	// }
   jQuery(".slidingDiv").hide('slow');
	
	jQuery('.show').click(function( e ){
        jQuery(this).toggleClass("hide").next(".slidingDiv").slideToggle('slow');
			  // scrollTo(jQuery('#footer'));
	});
	
	jQuery('.ui-tabs-anchor').click(function( e ){
		if(jQuery('.insertquestionbank').hasClass('ui-tabs-active')) {
			
			jQuery("#questionbottom").hide();
			jQuery("#importquestiondata").hide();
			
		}else{
			jQuery("#questionbottom").show();
			jQuery("#importquestiondata").show();
		}
	});
	
});

});//]]>  

</script>
<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
var json = '<?php echo $Getcategory ?>';

var json_parsed = $.parseJSON(json);

for (var u = 0; u < json_parsed.categories.length; u++){
    var getcategories = json_parsed.categories[u];
	var tableft =  "tableftcategory"+getcategories.id;
    $('ul.questionbank').append($('<li>').html('<a class="tableftcategory" id='+getcategories.id+' rel='+tableft+'>'+getcategories.name));
}

});//]]>  

</script>
<script type='text/javascript'>
	$(document).ready(function () {
		// show/hide tab left
		$('ul.questions > li.question').show();
		$(document).on('click','a.tableftcategory', function(e) {
			e.preventDefault();
			$("a.tableftcategory").removeClass("selected");
			$(this).addClass('selected');
			$('ul.questions > li.question').addClass('show');
			if($("a.tableftcategory").hasClass("selected"))
			{
				$('ul.questions > li.' + $(this).attr('rel')).removeClass("show");
				$('ul.questions > li.' + $(this).attr('rel')).show(500);
				console.log("ok");
				$('ul.questions > li.show').hide(500);
				console.log("no");
			};
			var myVar;
			var myVar2;
			eval('myVar = '+$('li.question:visible').length+';');
			eval('myVar2 = '+$('li.show:visible').length+';');
			
			console.log(myVar - myVar2);
		});
		// checkbox all
		$('#checkAll').click(function () {    
			 $('input:checkbox.checkboxQuestion').prop('checked', this.checked);    
		});
		// show/hide select category
		$('.sortCategory').on('change', function(){
			$("option.optionCategory").removeClass("selected");
			$(this).children().addClass('selected');
			$('ul.questions > li.question').addClass('show');
			if($("option.optionCategory").hasClass("selected"))
				{
					$('ul.questions > li.tableftcategory' + $(this).val()).removeClass("show");
					$('ul.questions > li.tableftcategory' + $(this).val()).show(500);
					console.log("ok");
					$('ul.questions > li.show').hide(500);
					console.log("no");
				} 
				if($(this).val() == 0){
				
					$('ul.questions > li.question').show(500);
					console.log("all");
				
				};
			
		});
		//ajax replace holder, double click
		$(function(){
			$('.replace-holder').editable({
				type: 'text',
				url: '/post',    
				pk: 1,    
				placement: 'top',
				title: 'Enter new text'    
			});



			//ajax emulation. Type "err" to see error message
			$.mockjax({
				url: '/post',
				responseTime: 400,
				response: function(settings) {
					if(settings.data.value == 'err') {
					   this.status = 500;  
					   this.responseText = 'Validation error!';
					} else {
					   this.responseText = '';  
					}
				}
			}); 

		});
		// filter question bank
			
			
		/* THE DEMO */
		$(function() {
			$('#search_term').filterFor('ul.questions', {caseSensitive : false, removeDiacritics : true});
		});

		/* THE ACTUAL CODE */
		(function($) {
			var settings;
			$.fn.extend({
				filterFor: function(listSelector, options) {
					settings = $.extend({
						 'caseSensitive' : true
					   , 'removeDiacritics' : false
					   // The list with keys to skip (esc, arrows, return, etc)
					   // 8 is backspace, removed for better usability
					   , keys : [13, 27, 32, 37, 38, 39, 40 /*,8*/ ]
					   //@TODO:use if set? , 'map' : {}
					   }, options)
					   , self = this
					   , $titles = $(listSelector)
					   , cache = {}
				   ;

				   if ($('li.question').attr('display') !== 'none') {
				   if ($titles.length !== 0 ) {
					   if(!$titles.is('ul,ol')){
						  $titles = $titles.find('ul,ol');
					   }
						//alert("check");
					   $titles = $titles.find('li');
					   $titles.each(function(index, node) {
						   var   $node = $(node)
							   , text = $node.text()
						   ;

						   if(settings.removeDiacritics === true){
							   text = text.removeDiacritics();
						   }

						   if(settings.caseSensitive === false){
							   text = text.toLowerCase();
						   }

						   if (typeof cache[text] !== 'undefined') {
							   // Another item with exactly the same text already exists
							   cache[text] = cache[text].add($node);
						   } else {
							   cache[text] = $node;
						   }
					   });

					   this.each(function(index, element) {
						   var $element = $(element);
						   $element.keyup(function(e) {
								if ($.inArray(e.keyCode, settings.keys) === -1) {
									var val = $element.val()
									
									if(settings.removeDiacritics === true){
										val = val.removeDiacritics();
									}
									
									if(settings.caseSensitive === false){
										val = val.toLowerCase();
									}

									$.each(cache, function(text, $node) {
									   if((text + '').indexOf(val) === -1) {
										   $node.hide();
									   } else {
										   $node.show();
									   }
								   });
							   }
						   });
					   });
				   }

				   return this;
			   }
			  }
		   });
		}(jQuery));
		
		// end filter question bank
	});   

</script>

<style>
.btn-small {
	font-size: 11px;
	padding: 4px 9px;
}
.teal {
	color: white;
	border-color: #049795;
	text-shadow: 0 -1px 0 rgba(68,68,68,0.3);
	background-color: #35c0c0;
	background: -webkit-gradient(linear, left top, left bottom, from(#35c0c0), to(#049795));
	background: -webkit-linear-gradient(top, #35c0c0, #049795);
	background: -moz-linear-gradient(top, #35c0c0, #049795);
	background: -ms-linear-gradient(top, #35c0c0, #049795);
	background: -o-linear-gradient(top, #35c0c0, #049795);
}
.btn {
	padding: 6px 13px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	-moz-background-clip: padding;
	-webkit-background-clip: padding-box;
	background-clip: padding-box;
	text-shadow: 0 1px 0 #fff;
	cursor: pointer;
	font-size: 13px;
	font-weight: bold;
	white-space: nowrap;
	text-decoration: none !important;
	color: #333;
	display: inline-block;
	border: solid 1px #bbb;
	background-color: #f0f0ea;
	background: -webkit-gradient(linear, left top, left bottom, from(#f0f0ea), to(#d5d7ce));
	background: -webkit-linear-gradient(top, #f0f0ea, #d5d7ce);
	background: -moz-linear-gradient(top, #f0f0ea, #d5d7ce);
	background: -ms-linear-gradient(top, #f0f0ea, #d5d7ce);
	background: -o-linear-gradient(top, #f0f0ea, #d5d7ce);
	position: relative;
}
li.question {
border-bottom: solid 1px #ccc;
padding: 10px 10px 10px 0;
letter-spacing: normal;
vertical-align: top;
word-spacing: normal;
white-space: normal;
}
.input-col {
padding: 0 5px 0 5px;
}
.question-text {
width: 96.8%;
}
.question-text {
vertical-align: top;
padding: 0;
margin: 0;
}
.question-text {
font-size: 14px;
}
.col {
display: table-cell;
vertical-align: top;
}
.button-col {
width: 8%;
position: relative;
text-align: right;
}
.subcategory {
padding: 10px 0 0 0;
color: #999;
}
.questions li.question:hover {
background: #eaeae8;
}
.questions {
border-top: solid 1px #ccc;
margin: 0px;
}
.search-input {
margin: 0 0 0 0;
}
.search-input {
padding: 10px 10px 10px 0;
}
.grid {
display: table;
width: 100%;
}
#select-all-copy {
background: #E6F5F7;
color: #333;
padding: 5px;
display: none;
text-align: center;
margin: 0;
}
.grid-u-3-5 {
width: 60%;
}
.search-label {
margin: 0;
padding: 0;
color: #999;
}
.search-box {
text-align: right;
}
.grid-u-2-5 {
width: 40%;
}
.search-icon {
margin: 0 5px 0 0;
width: 24px;
display: inline-block;
zoom: 1;
}
.search-box .search {
color: #999;
font-style: italic;
font-family: arial,helvetica,san-serif;
}
.search-input input {
width: 80%;
font-size: 12px;
color: #333;
height: 18px;
}
.search-input .submit {
width: 24px;
height: 22px;
display: inline-block;
border: 0;
margin: 0 5px;
background: #fff url(../../../../../../../../../../assets/img/btn_search.png) no-repeat 0 0;
cursor: pointer;
}
.titlequestionbank{
	padding: 20px;
}
.titlequestionbank > a{
	font-weight: 700;
	text-decoration: blink;
}
</style>
<?php PrepareEditorScript(true, $this); ?>

<script type='text/javascript'><?php echo $qTypeOutput; ?></script>

<div class='header ui-widget-header'>
    <?php 
    if ($adding) { ?>
        <?php $clang->eT("Add a new question"); ?>
        <?php } elseif ($copying) { ?>
        <?php $clang->eT("Copy question"); ?>
        <?php } else { ?>
        <?php $clang->eT("Edit question"); ?>
        <?php } ?>

</div>
<div class="loader2"></div>
<div id='tabs'>
    <ul>

        <li><a href="#<?php echo $eqrow['language']; ?>"><?php echo getLanguageNameFromCode($eqrow['language'],false); ?>
                (<?php $clang->eT("Base language"); ?>)
            </a></li>
		
        <?php
            $addlanguages=Survey::model()->findByPk($surveyid)->additionalLanguages;
            foreach  ($addlanguages as $addlanguage)
            { ?>
            <li><a href="#<?php echo $addlanguage; ?>"><?php echo getLanguageNameFromCode($addlanguage,false); ?>
                </a></li>
            <?php }
        ?>
		<li class="insertquestionbank">
			<a href="#insert-question-bank" >Insert from questionbank</a>
		</li>
    </ul>
    <?php echo CHtml::form(array("admin/database/index"), 'post',array('class'=>'form30','id'=>'frmeditquestion','name'=>'frmeditquestion','onsubmit'=>"return isEmpty(document.getElementById('title'), '".$clang->gT("Error: You have to enter a question code.",'js')."');")); ?>
            <div id='questionactioncopy' class='extra-action'>
                <p><input type='submit' class="saveandreturn" value='<?php $clang->eT("Save") ?>' />
                <input type='submit' value='<?php $clang->eT("Save and close"); ?>' />
            </div>

            <div id="<?php echo $eqrow['language']; ?>">
            <?php $eqrow  = array_map('htmlspecialchars', $eqrow); ?>
                <ul><li>
                        <?php if($eqrow['title']) {$sPattern="^([a-zA-Z][a-zA-Z0-9]*|{$eqrow['title']})$";}else{$sPattern="^[a-zA-Z][a-zA-Z0-9]*$";} ?>
                        <label for='title'> <?php $clang->eT("Code:"); ?></label><input type='text' size='20' maxlength='20' id='title' required='required' name='title' pattern='<?php echo $sPattern ?>' value="<?php echo $eqrow['title']; ?>" /> <?php if ($copying) $clang->eT("Note: You MUST enter a new question code!"); ?>
                    </li><li>
                        <label for='question_<?php echo $eqrow['language']; ?>'><?php $clang->eT("Question:"); ?></label>
                        <div class="htmleditor">
                        <textarea cols='50' rows='4' id='question_<?php echo $eqrow['language']; ?>' name='question_<?php echo $eqrow['language']; ?>'><?php echo $eqrow['question']; ?></textarea>
                        </div>
                        <?php echo getEditor("question-text","question_".$eqrow['language'], "[".$clang->gT("Question:", "js")."](".$eqrow['language'].")",$surveyid,$gid,$qid,$action); ?>
                    </li><li>
                        <label for='help_<?php echo $eqrow['language']; ?>'><?php $clang->eT("Help:"); ?></label>
                        <div class="htmleditor">
                        <textarea cols='50' rows='4' id='help_<?php echo $eqrow['language']; ?>' name='help_<?php echo $eqrow['language']; ?>'><?php echo $eqrow['help']; ?></textarea>
                        </div>
                        <?php echo getEditor("question-help","help_".$eqrow['language'], "[".$clang->gT("Help:", "js")."](".$eqrow['language'].")",$surveyid,$gid,$qid,$action); ?>
                    </li>
                </ul>
            </div>
			<div id="insert-question-bank" class="insert" style="border-top:1px solid #999; margin-top:20px;">
                <div style="float:left; background:#dfe0d8; width:20%; padding:0;">
						<div class="titlequestionbank"><a>Question Bank</a></div>
					<ul class="questionbank">
						
					</ul>
				</div>
                <div style="float:right; background:#fff; width:79%; margin-left:10px;padding:0;">
					<h1 style="font-size:1.5em;">Question Bank</h1>
					<div style="clear:both;"></div>
					<div style="background:#eaeae8;padding: 10px; overflow: hidden;">
						<div style="float:left;">
							<strong>Showing: </strong>
							<select class="sortCategory">
								<option value="0">All Question</option>
							</select>
						</div>
						<div style="float:right;">
							<a class="btn btn-small teal addBtn" href="javascript:AddkMulti();" data-active="">Add to Survey</a>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div class="grid search-input">
					  <div class="grid-u-3-5 col paging-top">
						<input style="width:auto;" type="checkbox" id="checkAll" name="checkAll" title="click to select all">
						<span class="search-label">
							Showing 1-<span class="notranslate"><?php echo $q ?></span> 
							of questions
						</span>
					  </div>
					  <div class="grid-u-2-5 col search-box">
						  <input placeholder="Search Questions" type="text" id="search_term" name="search_term" class="search" maxlength="100" value="" title="Search Questions">
					  </div>
					</div>
					<div style="clear:both;"></div>
					<ul class="questions">
						
						
					</ul>
				</div>
            </div>


        <?php if (!$adding)
            {

                foreach ($aqresult as $aqrow)
                {
                    $aqrow = $aqrow->attributes;
                    ?>

                <div id="<?php echo $aqrow['language']; ?>">
                    <ul>
                        <?php $aqrow  = array_map('htmlspecialchars', $aqrow); ?>
                        <li>
                            <label for='question_<?php echo $aqrow['language']; ?>'><?php $clang->eT("Question:"); ?></label>
                            <div class="htmleditor">
                            <textarea cols='50' rows='4' id='question_<?php echo $aqrow['language']; ?>' name='question_<?php echo $aqrow['language']; ?>'><?php echo $aqrow['question']; ?></textarea>
                            </div>
                            <?php echo getEditor("question-text","question_".$aqrow['language'], "[".$clang->gT("Question:", "js")."](".$aqrow['language'].")",$surveyid,$gid,$qid,$action); ?>
                        </li><li>
                            <label for='help_<?php echo $aqrow['language']; ?>'><?php $clang->eT("Help:"); ?></label>
                            <div class="htmleditor">
                            <textarea cols='50' rows='4' id='help_<?php echo $aqrow['language']; ?>' name='help_<?php echo $aqrow['language']; ?>'><?php echo $aqrow['help']; ?></textarea>
                            </div>
                            <?php echo getEditor("question-help","help_".$aqrow['language'], "[".$clang->gT("Help:", "js")."](".$aqrow['language'].")",$surveyid,$gid,$qid,$action); ?>
                        </li>

                    </ul>
                </div>
                <?php }
            }
            else
            {
                $addlanguages=Survey::model()->findByPk($surveyid)->additionalLanguages;
                foreach  ($addlanguages as $addlanguage)
                { ?>
                <div id="<?php echo $addlanguage; ?>">
                    <ul>
                        <li>
                            <label for='question_<?php echo $addlanguage; ?>'><?php $clang->eT("Question:"); ?></label>
                             <div class="htmleditor">
                            <textarea cols='50' rows='4' id='question_<?php echo $addlanguage; ?>' name='question_<?php echo $addlanguage; ?>'></textarea>
                            </div>

                            <?php echo getEditor("question-text","question_".$addlanguage, "[".$clang->gT("Question:", "js")."](".$addlanguage.")",$surveyid,$gid,$qid,$action); ?>
                        </li><li>
                            <label for='help_<?php echo $addlanguage; ?>'><?php $clang->eT("Help:"); ?></label>
                            <div class="htmleditor">
                            <textarea cols='50' rows='4' id='help_<?php echo $addlanguage; ?>' name='help_<?php echo $addlanguage; ?>'></textarea>
                            </div>
                            <?php echo getEditor("question-help","help_".$addlanguage, "[".$clang->gT("Help:", "js")."](".$addlanguage.")",$surveyid,$gid,$qid,$action); ?>
                        </li></ul>
                </div>
                <?php }
        } ?>
        <div id='questionbottom'>
            <ul>
                <li><label for='question_type'><?php $clang->eT("Question Type:"); ?></label>
                    <?php if ($activated != "Y")
                        {
                            if($selectormodeclass!="none")
                            {
                                foreach (getQuestionTypeList($eqrow['type'], 'array') as $key=> $questionType)
                                {
                                    if (!isset($groups[$questionType['group']]))
                                    {
                                        $groups[$questionType['group']] = array();
                                    }
                                    $groups[$questionType['group']][$key] = $questionType['description'];
                                }
                                $this->widget('ext.bootstrap.widgets.TbSelect2', array(
                                    'data' => $groups,
                                    'name' => 'type',
                                    'options' => array(
                                        'width' => '300px',
                                        'minimumResultsForSearch' => 1000
                                    ),
                                    'events' => array(
                                    ),
                                    'htmlOptions' => array(
                                        'id' => 'question_type',
                                        'options' => array(
                                        $eqrow['type']=>array('selected'=>true))
                                    )
                                ));
                                $script = '$("#question_type option").addClass("questionType");';
                                App()->getClientScript()->registerScript('add_class_to_options', $script);
                            }
                            else
                            {
                                $aQtypeData=array();
                                foreach (getQuestionTypeList($eqrow['type'], 'array') as $key=> $questionType)
                                {
                                    $aQtypeData[]=array('code'=>$key,'description'=>$questionType['description'],'group'=>$questionType['group']);
                                }
                                echo CHtml::dropDownList('type','category',CHtml::listData($aQtypeData,'code','description','group'),
                                    array('class' => 'none','id'=>'question_type','options' => array($eqrow['type']=>array('selected'=>true)))
                                );
                            }
                        }
                        else
                        {
                            $qtypelist=getQuestionTypeList('','array');
                            echo "{$qtypelist[$eqrow['type']]['description']} - ".$clang->gT("Cannot be changed (survey is active)"); ?>
                            <input type='hidden' name='type' id='question_type' value='<?php echo $eqrow['type']; ?>' />
                        <?php } ?>

                </li>



                <?php if ($activated != "Y")
                    { ?>
                    <li>
                        <label for='gid'><?php $clang->eT("Question group:"); ?></label>
                        <select name='gid' id='gid'>

                            <?php echo getGroupList3($eqrow['gid'],$surveyid); ?>
                        </select></li>
                    <?php }
                    else
                    { ?>
                    <li>
                        <label><?php $clang->eT("Question group:"); ?></label>
                        <?php echo $eqrow['group_name']." - ".$clang->gT("Cannot be changed (survey is active)"); ?>
                        <input type='hidden' name='gid' value='<?php echo $eqrow['gid']; ?>' />
                    </li>
                    <?php } ?>
                <li id='OtherSelection'>
                    <label><?php $clang->eT("Option 'Other':"); ?></label>

                    <?php if ($activated != "Y")
                        { ?>
                        <label for='OY'><?php $clang->eT("Yes"); ?></label><input id='OY' type='radio' class='radiobtn' name='other' value='Y'
                            <?php if ($eqrow['other'] == "Y") { ?>
                                checked
                                <?php } ?>
                            />&nbsp;&nbsp;
                        <label for='ON'><?php $clang->eT("No"); ?></label><input id='ON' type='radio' class='radiobtn' name='other' value='N'
                            <?php if ($eqrow['other'] == "N" || $eqrow['other'] == "" ) { ?>
                                checked='checked'
                                <?php } ?>
                            />
                        <?php }
                        else
                        {
                            if($eqrow['other']=='Y') $clang->eT("Yes"); else $clang->eT("No");
                            echo " - ".$clang->gT("Cannot be changed (survey is active)"); ?>
                        <input type='hidden' name='other' value="<?php echo $eqrow['other']; ?>" />
                        <?php } ?>
                </li>

                <li id='MandatorySelection'>
                    <label><?php $clang->eT("Mandatory:"); ?></label>
                    <label for='MY'><?php $clang->eT("Yes"); ?></label> <input id='MY' type='radio' class='radiobtn' name='mandatory' value='Y'
                        <?php if ($eqrow['mandatory'] == "Y") { ?>
                            checked='checked'
                            <?php } ?>
                        />&nbsp;&nbsp;
                    <label for='MN'><?php $clang->eT("No"); ?></label> <input id='MN' type='radio' class='radiobtn' name='mandatory' value='N'
                        <?php if ($eqrow['mandatory'] != "Y") { ?>
                            checked='checked'
                            <?php } ?>
                        />
                </li>
                <li>
                    <label for='relevance'><?php $clang->eT("Relevance equation:"); ?></label>
                    <textarea cols='50' rows='1' id='relevance' name='relevance' <?php if ($eqrow['conditions_number']) {?> readonly='readonly'<?php } ?>><?php echo $eqrow['relevance']; ?></textarea>
                     <?php if ($eqrow['conditions_number']) {?>
                        <span class='annotation'> <?php $clang->eT("Note: You can't edit the relevance equation because there are currently conditions set for this question."); ?></span>
                     <?php } ?>
                </li>

                <li id='Validation'>
                    <label for='preg'><?php $clang->eT("Validation:"); ?></label>
                    <input type='text' id='preg' name='preg' size='50' value="<?php echo $eqrow['preg']; ?>" />
                </li>


                <?php if ($adding) {
                        if (count($oqresult)) { ?>

                        <li>
                            <label for='questionposition'><?php $clang->eT("Position:"); ?></label>
                            <select name='questionposition' id='questionposition'>
                                <option value=''><?php $clang->eT("At end"); ?></option>
                                <option value='0'><?php $clang->eT("At beginning"); ?></option>
                                <?php foreach ($oqresult as $oq)
                                    {
                                        $oq = $oq->attributes;
                                    ?>
                                    <?php $question_order_plus_one = $oq['question_order']+1; ?>
                                    <option value='<?php echo $question_order_plus_one; ?>'><?php $clang->eT("After"); ?>: <?php echo $oq['title']; ?></option>
                                    <?php } ?>
                            </select>
                        </li>
                        <?php }
                        else
                        { ?>
                        <input type='hidden' name='questionposition' value='' />
                        <?php }
                } elseif ($copying) { ?>

					<li>
						<label for='copysubquestions'><?php $clang->eT("Copy subquestions?"); ?></label>
						<input type='checkbox' class='checkboxbtn' checked='checked' id='copysubquestions' name='copysubquestions' value='Y' />
					</li>
					<li>
						<label for='copyanswers'><?php $clang->eT("Copy answer options?"); ?></label>
						<input type='checkbox' class='checkboxbtn' checked='checked' id='copyanswers' name='copyanswers' value='Y' />
					</li>
					<li>
						<label for='copyattributes'><?php $clang->eT("Copy advanced settings?"); ?></label>
						<input type='checkbox' class='checkboxbtn' checked='checked' id='copyattributes' name='copyattributes' value='Y' />
					</li>

				<?php } ?>

            </ul>

			<?php if (!$copying) { ?>
				<p><a id="showadvancedattributes"><?php $clang->eT("Show advanced settings"); ?></a><a id="hideadvancedattributes" style="display:none;"><?php $clang->eT("Hide advanced settings"); ?></a></p>
				<div id="advancedquestionsettingswrapper" style="display:none;">
					<div class="loader"><?php $clang->eT("Loading..."); ?></div>
					<div id="advancedquestionsettings"></div>
				</div><br />
			<?php } ?>
                <?php if ($adding)
                    { ?>
                    <input type='hidden' name='action' value='insertquestion' />
                    <input type='hidden' name='gid' value='<?php echo $eqrow['gid']; ?>' />
					<p><input type='submit' value='<?php $clang->eT("Add question"); ?>' />
                    <?php }
                    elseif ($copying)
                    { ?>
                    <input type='hidden' name='action' value='copyquestion' />
                    <input type='hidden' id='oldqid' name='oldqid' value='<?php echo $qid; ?>' />
					<p><input type='submit' value='<?php $clang->eT("Copy question"); ?>' />
                    <?php }
                    else
                    { ?>
                    <input type='hidden' name='action' value='updatequestion' />
                    <input type='hidden' id='newpage' name='newpage' value='' />
                    <input type='hidden' id='qid' name='qid' value='<?php echo $qid; ?>' />
					<p><input type='submit' class="saveandreturn" value='<?php $clang->eT("Save") ?>' />
                    <input type='submit' value='<?php $clang->eT("Save and close"); ?>' />
                    <?php } ?>
                <input type='hidden' id='sid' name='sid' value='<?php echo $surveyid; ?>' /></p><br />
        </div></form></div>



<?php if ($adding)
    {


        if (Permission::model()->hasSurveyPermission($surveyid,'surveycontent','import'))
        { ?>
		<div id="importquestiondata">
        <br /><div class='header ui-widget-header'><?php $clang->eT("...or import a question"); ?></div>
        <?php echo CHtml::form(array("admin/questions/sa/import"), 'post', array('id'=>'importquestion', 'name'=>'importquestion', 'enctype'=>'multipart/form-data','onsubmit'=>"return validatefilename(this, '".$clang->gT("Please select a file to import!",'js')."');")); ?>
            <ul>
                <li>
                    <label for='the_file'><?php $clang->eT("Select LimeSurvey question file (*.lsq/*.csv)"); ?>:</label>
                    <input name='the_file' id='the_file' type="file"/>
                </li>
                <li>
                    <label for='translinksfields'><?php $clang->eT("Convert resource links?"); ?></label>
                    <input name='translinksfields' id='translinksfields' type='checkbox' checked='checked'/>
                </li>
            </ul>
            <p>
            <input type='submit' value='<?php $clang->eT("Import Question"); ?>' />
            <input type='hidden' name='action' value='importquestion' />
            <input type='hidden' name='sid' value='<?php echo $surveyid; ?>' />
            <input type='hidden' name='gid' value='<?php echo $gid; ?>' />
        </form>
		</div>
        <?php } ?>

    <script type='text/javascript'>
        <!--
        document.getElementById('title').focus();
        //-->
    </script>

    <?php } ?>
