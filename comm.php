<?php
require_once 'config.php';

/**
 * @fileName comm
 * @author Amir <amirsanni@gmail.com>
 * @date 22-Dec-2016
 */

?>

<!DOCTYPE html>
<html lang="en">
    <head>    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Chat App</title>
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico">
        <!-- favicon ends -->
        
        <!--- LOAD FILES -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome-animation/0.0.8/font-awesome-animation.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Custom styles -->
        <link rel="stylesheet" href="css/comm.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    
    
    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Remote Video -->
                <video id="peerVid" poster="img/vidbg.png" playsinline autoplay></video>
                <!-- Remote Video -->
            </div>
            
            <div class="row margin-top-20">                
                <!-- Call Buttons -->
                <div class="col-sm-12 text-center" id="callBtns">
                    <button class="btn btn-success btn-sm initCall" id="initAudio"><i class="fa fa-phone"></i></button>
                    <button class="btn btn-info btn-sm initCall" id="initVideo"><i class="fa fa-video-camera"></i></button>
                    <button class="btn btn-danger btn-sm" id="terminateCall" disabled><i class="fa fa-phone-square"></i></button>
                </div>
                <!-- Call Buttons -->
                
                <!-- Timer -->
                <div class="col-sm-12 text-center margin-top-5" style="color:#fff">
                    <span id="countHr"></span>h:
                    <span id="countMin"></span>m:
                    <span id="countSec"></span>s
                </div>
                <!-- Timer -->
            </div>
            
            
            <!-- Local Video -->
            <div class="row">
                <div class="col-sm-12">
                    <video id="myVid" poster="img/vidbg.png" muted autoplay></video>
                </div>
            </div>
            <!-- Local Video -->
        </div>

        <div class="container-fluid chat-pane">
            <!-- CHAT PANEL-->
            <div class="row chat-window col-xs-12 col-md-4">
                <div class="">
                    <div class="panel panel-default chat-pane-panel">
                        <div class="panel-heading chat-pane-top-bar">
                            <div class="col-xs-10" style="margin-left:-20px">
                                <i class="fa fa-comment" id="remoteStatus"></i> Remote
                                <b id="remoteStatusTxt">(Offline)</b>
                            </div>
                            <div class="col-xs-2 pull-right">
                                <span id="minim_chat_window" class="panel-collapsed fa fa-plus icon_minim pointer"></span>
                            </div>
                        </div>
                        
                        <div class="panel-body msg_container_base" id="chats"></div>
                        
                        <div class="panel-footer">
                            <span id="typingInfo"></span>
                            <div class="input-group">
                                <input id="chatInput" type="text" class="form-control input-sm chat_input" placeholder="Type message here...">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm" id="chatSendBtn">Send</button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CHAT PANEL -->
        </div>

        <!--Modal to show that we are calling-->
        <div id="callModal" class="modal fade" role="dialog" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header" id="callerInfo"></div>

                    <div class="modal-body">
                        <button type="button" class="btn btn-danger btn-sm" id='endCall'>
                            <i class="fa fa-times-circle"></i> End Call
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal end-->


        <!--Modal to give options to receive call-->
        <div id="rcivModal" class="modal fade" role="dialog" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id="calleeInfo"></div>

                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-success btn-sm answerCall" id='startAudio'>
                            <i class="fa fa-phone"></i> Audio Call
                        </button>
                        <button type="button" class="btn btn-success btn-sm answerCall" id='startVideo'>
                            <i class="fa fa-video-camera"></i> Video Call
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id='rejectCall'>
                            <i class="fa fa-times-circle"></i> Reject Call
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal end-->
        
        <!--Modal to show flash message-->
        <div id="flashMsgModal" class="modal fade" role="dialog" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" id="flashMsgHeader">
                        <button type="button" class="close" id='closeFlashMsg' data-dismiss="modal">&times;</button>
                        <center><i id="flashMsgIcon"></i> <font id="flashMsg"></font></center>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal end-->
        
        <!--Snackbar -->
        <div id="snackbar"></div>
        <!-- Snackbar -->

        <!-- custom js -->
        <script>
            var room = "<?=filter_input(INPUT_GET, 'room')?>";
            var wsChat = new WebSocket("<?=WS_URL?>");
        </script>
        <script src="js/adapter.js"></script>
        <script src="js/comm.js"></script>
        <audio id="callerTone" src="media/callertone.mp3" loop preload="auto"></audio>
        <audio id="msgTone" src="media/msgtone.mp3" preload="auto"></audio>
    </body>
</html>