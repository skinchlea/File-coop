<?php
   require "header.php";
   //echo 'Current PHP version: ' . phpversion();
   ?>

<main>

    <?php
      //Check if user is logged in
      if(isset($_SESSION['userID'])){
        //IS LOGGED IN
        $userID = $_SESSION['userID'];
      } else {
            //IS LOGGED OUT
            header("Location: login.php");
            exit();
      }
      ?>



    <div class="pageLayout">
        <div class="sidebarContainer">
            <div class="sidebar">
                <!-- <img src="images/arrow.png"> -->
                <div id="sidebarContent">
                    <!-- <h1>My Hubs</h1> -->
                    <div class="buttonWrapper"><button type="button" class="newHub" onclick="newHub()">Create
                            Hub</button></div>
                </div>
                <ul id="hubList"></ul>
            </div>
        </div>
        <div class="dashboard">
            <div class="dashTitle">
                <h1 id="dashTitle">Hub Name</h1>
                <img src="icons/gear.svg"/>
            </div>
            <div class="panelContainer">
                <div class="dashPanel border panel1">
                    <h1>Hub Files</h1>
                    <div style="padding:10px; padding-top:20px;">
                        <button id="fileSelect" type="button" class="actionbutton"><i class="fi-xnsuxl-file-solid"
                                title="New File"></i>&nbsp;New File</button>
                        <button id="upload" class="actionbutton"><i
                                class="fi-xnsuxl-upload-solid"></i>&nbsp;&nbsp;Upload
                            File</button>
                        <button type="button" id="newFolder" class="actionbutton" onclick="createFolder()"
                            title="New Folder"><i class="fi-xwsuxl-folder-solid"></i>&nbsp;&nbsp;New Folder</button>
                        <span id="filename">No File Selected</span>
                        <input id="newFile" type="file" name="newfile" style="display:none" />
                        <div id="folderHolder"></div>
                        <div id="fileHolder"></div>
                    </div>
                </div>
                <div class="dashPanel border panel2"></div>
            </div>


        </div>
    </div>

    <div class="wholePage" id="wp">
    </div>
    <div class="wpContainer flexContainer" id="wpc" style="display:none">
        <img id="closewp" src="icons/close.svg" onclick="closewp()" />
        <div class="panel flex3" id="previewPanel">
            <iframe id="previewFrame" src="javascript:;"></iframe>
        </div>
        <div class="panel flex2 flexContainer" id="chatPanel">
            <div class="flex1 RP1">
                <h1 id="boardName">File Board</h1>
            </div>
            <div class="flex10 RP2"></div>
            <div class="flex1 RP3 flexContainer">
                <div class="flex10"><textarea class="msgInput" placeholder="Enter a comment here..."></textarea>
                </div>
                <div class="flex1"><button type="button" class="msgButton" id="sendMsg"><i
                            class="fa fa-paper-plane"></i></button></div>
            </div>
        </div>
    </div>

    <div class="mainPage flexContainer" style="display:none">
        <div class="bottomPopup" onclick="openHistory()"><a href="#ex2" rel="modal:open" style="display:none;">Update
                History</a></div>
        <div class="flex1" id="panel1"></div>
        <div class="panel flex3 flexContainer" id="filePanel">
            <div class="flex1" style="text-align:center;">
                <button type="button" id="inviteNotice">New Invites</button>

            </div>
            <div class="flex10 flexContainer" id="fileHub">
                <div class="flex1 innerPanel" id="lp1">
                    <!-- <div id="folderHolder"></div>
                    <div id="fileHolder"></div> -->
                </div>
                <div class="flex1 innerPanel" id="lp2">
                    <h1 class="heading" id="headinglp2">Shared With Me</h1>
                </div>
            </div>
        </div>
        <div class="flex1" id="panel3"></div>
    </div>

    <!-- SHARE FILE MODAL -->
    <div id="ex1" class="modal">
        <h1>Collaborate on File</h1>
        <input type="text" class="inviteEmail" placeholder="Email to Invite" />
        <a href="#" class="mLink" id="sendInvite" href="#">Submit</a>
        <span id="emailValid"></span>
    </div>

    <!-- CREATE HUB MODAL -->
    <div id="ex5" class="modal">
        <h1>Create Hub</h1>
        <input type="text" class="hubName" placeholder="Hub Name" />
        <a href="#" class="mLink" id="createHub" href="#" onclick="createHub()">Create</a>
        <!-- <span id="emailValid"></span> -->
    </div>


    <div id="ex2" class="modal">
    </div>

    <!-- REPLACE FILE MODAL -->
    <div id="ex3" class="modal">
        <input type="file" name="rFile" />
        <button type="button">Replace</button>
    </div>
    <div id="ex4" class="modal">
        <h1>File Invitations</h1>
    </div>
</main>
<?php
   require "footer.php";

   ?>


<script src="scripts/functions.js"></script>