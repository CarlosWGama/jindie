<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<div id="chat-jindie">
	<!-- Latest compiled and minified CSS -->
		

	<style scoped>
		/* chat */
		#chat-jindie .panel { border: 1px solid transparent; border-radius: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.05); }
		#chat-jindie .panel-heading { padding: 10px 15px;}
		#chat-jindie .panel-heading { padding: 10px 15px;}
		#chat-jindie .panel-heading { padding: 10px 15px;}
		#chat-jindie .panel-primary{border-color:#337ab7}	
		#chat-jindie .panel-primary>.panel-heading {color: #fff; background-color: #337ab7; border-color: #337ab7;}
		#chat-jindie .panel-footer { padding: 10px 15px; background-color: #f5f5f5; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px;}
		#chat-jindie .input-group-btn:last-child>.btn-chat {z-index: 2; margin-left: -1px;}
		#chat-jindie .input-group { display: table; width: 100%;}
		#chat-jindie .input-group-btn { display: table-cell; }
		#chat-jindie .input-sm { height: 30px; padding: 5px 10px; font-size: 12px; line-height: 1.5; border-radius: 3px;}
		#chat-jindie .input-group-btn:last-child>.btn-chat { border-top-left-radius: 0; border-bottom-left-radius: 0;}
		#chat-jindie .btn-chat { position: relative; color: #fff; background-color: #f0ad4e; border-color: #eea236; padding: 5px 10px;font-size: 12px; line-height: 1.5; border-radius: 3px;	cursor: pointer; border: 1px solid transparent;}
		#chat-jindie .form-control { color: #555; background-color: #fff; border: 1px solid #ccc; width: 100%;}		
		#chat-jindie .chat { overflow: auto; height: 250px;}
		#chat-jindie .chat li {list-style-type:none; margin-bottom:10px;}
		#chat-jindie p {font-size: 13px; margin: 0 0 0.2rem 0;}
		#chat-jindie time {font-size: 11px; color: #ccc; }
		#chat-jindie img { width: 50px;}
		#chat-jindie .invert-img { -moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1);transform: scaleX(-1);}
		#chat-jindie .pull-left{padding-right:10px;float: left!important;}
		#chat-jindie .img-circle{border-radius:50%}
		#chat-jindie .pull-right{float: right; padding-right:10px;}		
	</style>

	<div class="panel panel-primary">

        <!-- HEADER -->
        <div class="panel-heading">
            Chat
        </div>
        <!-- END HEADER -->

        <!-- MESSAGES -->
        <div class="panel-body">
            <ul class="chat">
            </ul>
        </div>
        <!-- END MESSAGES -->

        <!-- COMMENT -->
        <div class="panel-footer">
            <div class="input-group">
                <!-- TEXTO -->
                <input name="comment" type="text" class="form-control input-sm" id="chat-comment" />

                <!-- BOTÃO -->
                <span class="input-group-btn">
                    <button class="btn-chat" onclick="javascript:sendMessage();"> >>> </button>
                </span>
            </div>
        </div>
        <!-- END COMMENT -->
    </div>


</div>


<script type="text/javascript">

	var lastCheck = "<?php echo date('Y-m-d H:i:s')?>";

	/*** SEND MESSAGE ***/
	function sendMessage() {
		$.post("<?php echo $linkSubmit ?>", {'message': $('#chat-comment').val(), 'last-check':lastCheck}, function (json) {
			addMessages(JSON.parse(json));
		});
		$('#chat-comment').val('');
	}
	/*** END SEND MESSAGE ***/

	/*** ADD MESSAGES ***/
	var left = true;
	function addMessages(json) {
		
		if (!jQuery.isEmptyObject(json)) {
			lastCheck = json.last_check;

			var html = "";
	        

		   	jQuery.each(json.comments, function(i, val){

	            if (left) {
	                html += '<li><span class="chat-img pull-left">';
	                if (val.avatar != null)
	                	html += '<img src="' + val.avatar + '" alt="' + val.name + '" class="img-circle" /></span>';
	                html += '<div><strong>' + val.name + '</strong></div>';
	                if (val.date != null)
	                	html += '<time>' + val.date + '</time>';
	                html += '<p>' + val.comment + '</p></li>';
	                html += '<div style="clear:both"></div>';
	                left = false;
	            } else {
	                html += '<li><span class="chat-img pull-right">';
	                if (val.avatar != null)
	                	html += '<img src="' + val.avatar + '" alt="' + val.name + '" class="img-circle invert-img" /></span>';
	                html += '<div><strong class="pull-right">'+ val.name + '</strong></div><br/>';
	                if (val.date != null)
	                	html += '<time class="pull-right">' + val.date + '</time><br/>';
	                html += '<p class="pull-right">'+ val.comment + '</p></li>';
	                left = true;
	            }
	        });
	        $(".chat").append(html);
	        $(".chat").animate({ scrollTop: $(document).height() }, "slow");
	        
		}
	}
	/*** END ADD MESSAGES ***/

	<?php if (isset($comments) && !empty($comments)): ?>
	addMessages(<?php echo $comments?>);
	<?php endif; ?>


	<?php if ($timeReload > 0 && !empty($linkReload)): ?>
	/*** RELOAD CHAT ***/
	function reload() {

		$.post("<?php echo $linkReload ?>", {'last-check':lastCheck}, function (json) {
			addMessages(JSON.parse(json));
		});
	};
	setInterval(function(){reload()}, <?php echo $timeReload ?>);
	/*** END RELOAD CHAT ***/
	<?php endif; ?>

</script>