var checked = false;
var invNumber = 0;

function closewp() {
    $("#wp").fadeOut();
    $("#wpc").fadeOut();
    $(".sidebar").fadeIn();
}

function previewFile(fileID) {
    $.ajax({
        type: "POST",
        url: 'includes/previewFile.inc.php',
        data: {
            previewFileID: fileID
        },
        success: function(data) {
            //Should return a filepath

            if (data != "") {
                $("#previewFrame").attr("src", data);
                $("#wpc").css("display", "flex");
                $("#wp").show();
                $("#wpc").show();
                $(".sidebar").hide();
            }

        }

    });
}

//Custom button to click oroginal file input button
$("#fileSelect").on("click", function() {
    $("#newFile").click();
});

//When the file input notices a change
$("#newFile").on("change", function() {

    if ($(this).val() != "") {

        //Get the orginal input value = filepath
        var fileOG = $("#newFile").val();

        //Cut the original value and get only the filename
        var fileActual = fileOG.substring(fileOG.lastIndexOf('\\') + 1, fileOG.length);

        //Append the filename to the span
        $("#filename").html(fileActual);

        //Change the text to black
        $("#filename").css("color", "black");

        //Show the upload confirm button and hide the select file button
        $("#upload").show();
        $("#fileSelect").hide();

    } else {

        $("#upload").hide();
        $("#fileSelect").show();
    }

});


//Upload File Submit Button
$('#upload').on('click', function() {

    if ($('#newFile').val() != "") {

        //Get the file in the file input
        var file_data = $('#newFile').prop('files')[0];

        //Add it to formdata
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('hubID', currentHubID);

        $.ajax({
            url: 'uploadfile.php',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data) {

                if (data != "Uploaded") {

                    var data = JSON.parse(data);

                    var size = filesize(data.fileSize);

                    var filetype = "";

                    var fullname = data.filename;
                    var extension = fullname.substring(fullname.length, fullname.length - 3);
                    if (extension == "pdf" || extension == "PDF") {
                        filetype = "pdf";
                    } else if (extension == "jpg" || extension == "jpeg" || extension == "JPG" || extension == "JPEG") {
                        filetype = "img";
                    }



                    var file = '<div class="fileOuter box flexContainer" id="file' + data.fileID + '" draggable="true" ondragstart="drag(event)">' +
                        '<div class="file flex3">' +
                        ((filetype == "pdf") ? '<img src="icons/pdf-file.svg" class="filetype"/>' : '<img class="filetype" src="icons/picture1.svg"/>') +
                        '<p>' + data.filename + '</p></div>' +
                        '<div class="flex1 fSize">' + size + '</div>' +
                        '<div class="optionsOuter flex1 flexContainer">' +
                        '<div class="fileOptions flex1 shareFile"><a href="#ex1" rel="modal:open"><img src="icons/add.svg" title="Share" onclick="shareFile(' + data.fileID + ')"/></a></div>' +
                        '<div class="fileOptions flex1 loadFile"><img src="icons/preview.svg" title="Preview" onclick="loadSharedFile(\'' + data.fileID + '\',\'' + data.filename + '\')"/></div>' +
                        '<div class="fileOptions flex1"><img src="icons/download.svg" title="Download" onclick="downloadFile(' + data.fileID + ')"/></div>' +
                        '<div class="fileOptions flex1 deleteFile"><img src="icons/trash.svg" title="Delete" id="' + data.fileID + '" onclick="deleteFile(\'' + data.fileID + '\',\'' + data.filename + '\')"/></div>' +
                        //'<div class="fileOptions flex1 moreFile" onclick="showMore(' + data.fileID + ')"><img src="icons/more.svg" title="More" id="' + data.fileID + '"/></div>' +
                        //'<div class="moreDropdown" id="dd' + data.fileID + '"><ul><li onclick="downloadFile(' + data.fileID + ')">Download</li></ul></div>' +
                        //<li onclick="replaceFile('+ data.fileID +')">Replace</li>
                        '</div>';


                    // Append the new file to the container on the frontend
                    $("#fileHolder").append(file);
                    $("#headinglp1").show();
                    $("#lp1").show();

                    // Clear the file upload inputted
                    $('#newFile').val('');

                    //Hide the upload confirm button & show the file select button
                    $("#upload").hide();
                    $("#fileSelect").show();

                    //Change the file selected text back to white
                    $("#filename").css("color", "white");

                    //Show the success messages$("body").overhang({
                    var message = "Uploaded " + data.filename;

                    showSuccess(message);
                } else {
                    //console.log("data is string");
                }


            }
        });

    }

});

var currentDropdown = "";

//Show the dropdown menu associated with each file
function showMore(data) {

    currentDropdown = $("#dd" + data).find("ul");

    if (!currentDropdown.is(":visible")) {
        currentDropdown.fadeIn();
    }

    // item.css("visibility", "visible");
    // item.css("pointer-events", "all");
}


//Accept INVITE - Function because elements are dynamic and I couldn't seem to register click event
function acceptInv(fileID) {

    var fileIDno = fileID;

    $.ajax({
        type: "POST",
        url: 'includes/acceptInv.inc.php',
        data: {
            acceptInv: fileIDno
        },
        dataType: 'json',
        success: function(data) {

            var size = filesize(data.fileSize);

            var file = '<div class="fileOuter box flexContainer" id="file' + data.fileID + '">' +
                '<div class="file flex3"><p>' + data.fileName + '</p></div>' +
                '<div class="flex1 fSize">' + size + '</div>' +
                '<div class="optionsOuter flex1 flexContainer">' +
                '<div class="fileOptions flex1"></div>' +
                '<div class="fileOptions flex1 loadSharedFile" onclick="loadSharedFile(\'' + data.fileID + '\',\'' + data.filename + '\')"><img src="icons/preview.svg" title="Preview"/></div>' +
                '<div class="fileOptions flex1"><img src="icons/download.svg" title="Download" onclick="downloadFile(' + data.fileID + ')"/></div>' +
                '<div class="fileOptions flex1 deleteFile" id="' + data.fileID + '"><img src="icons/trash.svg" title="Delete" onclick="deleteShared(\'' + data.fileID + '\',\'' + data.filename + '\')"/></div>' +
                //'<div class="fileOptions flex1 moreFile" onclick="showMore(' + data.fileID + ')"><img src="icons/more.svg" title="More" id="' + data.fileID + '"/></div>' +
                //'<div class="moreDropdown" id="dd' + data.fileID + '"><ul><li title="Download File" onclick="downloadFile(' + data.fileID + ')">Download</li></ul></div>' +
                '</div></div>';


            // Append the new file to the container on the frontend
            $("#lp2").append(file);
            $("#lp2").show();

            //Remove the invite on the frontend
            $("#invite" + fileIDno).remove();
            invNumber--;

            //No more invites, close modal
            if ($('#ex4').find(".invite").length == 0) {
                $.modal.close();
            }

        }

    });

}

//REJECT INVITE - Function because elements are dynamic and I couldn't seem to register click event
function rejectInv(fileID) {

    var fileIDno = fileID;

    $.confirm({
        title: 'Delete Invitation',
        content: 'Are you sure you want to delete this invitation?',
        buttons: {
            Delete: {
                text: 'Delete',
                btnClass: 'deleteConfirm',
                action: function() {
                    $.ajax({
                        type: "POST",
                        url: 'includes/rejectInv.inc.php',
                        data: {
                            fileInv: fileIDno
                        },
                        success: function(data) {
                            //Remove the invite on the frontend
                            $("#invite" + fileIDno).remove();
                            invNumber--;

                            //No more invites, close modal
                            if ($('#ex4').find(".invite").length == 0) {
                                $.modal.close();
                            }

                        }

                    });
                }
            },
            cancel: function() {

            },
        }
    });


}

var InviteCount = 0;
var filesStart = "1";
var folderStart = "1";
var currentHubID = "x";

// ON PAGE LOAD

$(window).on("load", function() {

    window.setInterval(checkfornew, 5000);

    // loadFolders();


    // if (InviteCount > 0) {
    //     $(".sidebar").show();
    // } else {
    //     $(".sidebar").hide();
    // }

    var hubStart = "1";

    $.ajax({
        type: "POST",
        url: 'includes/loadhublist.inc.php',
        data: {
            loadhub: hubStart
        },
        success: function(data) {

            //If there is data
            if (data != "No results found") {

                var hublist = JSON.parse(data);

                for (var i = 0; i < hublist.length; i++) {
                    //var hublink = '<a href="#" class="hubA" id="' + hublist[i].hubID + '">' + hublist[i].hubName + '</a>';
                    var hublink = '<li class="hubA" id="' + hublist[i].hubID + '" onclick="openHub(\'' + hublist[i].hubID + '\',\'' + hublist[i].hubName + '\')">' + hublist[i].hubName + '</li>';

                    $("#hubList").append(hublink);
                }

            } else {

            }
        }

    });


    var inv = "1";
    var filecheck = "1";

    // CHECK FOR SHARED FILES
    $.ajax({
        type: "POST",
        url: 'includes/filecheck.inc.php',
        data: {
            fCheck: filecheck
        },
        dataType: 'json',
        success: function(data) {

            filecheck = "";

            //If there is data
            if (data != "No results found") {

                var sharedFiles = data;

                for (var i = 0; i < sharedFiles.length; i++) {

                    var size = filesize(sharedFiles[i].fileSize);

                    var newshared = '<li class="fileOuter box flexContainer" id="file' + sharedFiles[i].fileID + '">' +
                        '<div class="file flex3"><p>' + sharedFiles[i].fileName + '</p></div>' +
                        '<div class="flex1 fSize">' + size + '</div>' +
                        '<div class="optionsOuter flex1 flexContainer">' +
                        '<div class="fileOptions flex1"></div>' +
                        '<div class="fileOptions flex1 loadSharedFile" onclick="loadSharedFile(\'' + sharedFiles[i].fileID + '\',\'' + sharedFiles[i].filename + '\')"><img src="icons/preview.svg" title="Preview"/></div>' +
                        '<div class="fileOptions flex1"><img src="icons/download.svg" title="Download" onclick="downloadFile(' + sharedFiles[i].fileID + ')"/></div>' +
                        '<div class="fileOptions flex1 deleteFile" id="' + sharedFiles[i].fileID + '"><img src="icons/trash.svg" title="Delete" onclick="deleteShared(\'' + sharedFiles[i].fileID + '\',\'' + sharedFiles[i].filename + '\')"/></div>' +
                        //'<div class="fileOptions flex1 moreFile" onclick="showMore(' + sharedFiles[i].fileID + ')"><img src="icons/more.svg" title="More" id="' + sharedFiles[i].fileID + '"/></div>' +
                        //'<div class="moreDropdown" id="dd' + sharedFiles[i].fileID + '"><ul><li title="Download File" onclick="downloadFile(' + sharedFiles[i].fileID + ')">Download</li></ul></div>' +
                        '</div></li>';

                    $("#lp2").append(newshared);
                    $("#lp2").show();
                }

            } else {

                $("#lp2").hide();
            }

        }

    });


    // CHECK FOR INVITES
    $.ajax({
        type: "POST",
        url: 'includes/invitecheck.inc.php',
        data: {
            invcheck: inv
        },
        success: function(data) {

            inv = "";

            //If there is data
            if (data != "No results found") {

                invNumber = 0;


                //Make data readable as an array in JS
                var invitedFiles = JSON.parse(data);

                //For each result returned, create a invite box
                for (var i = 0; i < invitedFiles.length; i++) {
                    var newInvite = '<div class="flexContainer box" id="invite' + invitedFiles[i].fileID + '">' +
                        '<div class="invite flex3" id="' + invitedFiles[i].fileID + '">' + invitedFiles[i].filename + '</div>' +
                        '<div class="flex1 flexContainer">' +
                        '<div class="flex1 inviteIcon" title="Accept Invite"><i class="fa fa-check icon acceptInvite" onclick="acceptInv(' + invitedFiles[i].fileID + ')"></i></div>' +
                        '<div class="flex1 inviteIcon" title="Remove Invite"><i class="fa fa-times icon delete rejectInvite" onclick="rejectInv(' + invitedFiles[i].fileID + ')"></i></div>' +
                        '</div>' +
                        '</div>';

                    //Apend the new invite to the invite panel
                    $("#ex4").append(newInvite);

                    invNumber++;

                }

                $('#ex4').modal();
                checked = true;


            } else {
                //No results found
                //$("#invitePanel").hide();
                //$(".sidebar").hide();
            }

        }

    });

});

function loadFolders() {

    $.ajax({
        type: "POST",
        url: 'includes/loadUserFolders.inc.php',
        data: {
            loadFoldersStart: folderStart,
            hubID: currentHubID
        },
        success: function(data) {

            console.log(data);

            //If there is data
            if (data != "No results found") {

                $("#lp1").show();

                var userFolders = JSON.parse(data);

                //console.log(userFolders);

                for (var i = 0; i < userFolders.length; i++) {


                    var newFolder = '<div class="fileOuter box flexContainer flexC folderContainer" id="file' + userFolders[i].folderID + '" ondrop="drop(event)" ondragover="allowDrop(event)">' +
                        '<div class="flex1 flexContainer flexR">' +
                        '<div class="file flex3">' +
                        '<img src="icons/folder.svg" class="filetype"/><p>' + userFolders[i].folderName + '</p>' +
                        '</div>' +
                        '<div class="optionsOuter flex1 flexContainer">' +
                        '<div class="fileOptions flex1 shareFolder"></div>' +
                        '<div class="fileOptions flex1"></div>' +
                        '<div class="fileOptions flex1"></div>' +
                        '<div class="fileOptions flex1"></div>' +
                        '<div class="fileOptions flex1"></div>' +
                        '<div class="fileOptions flex1 deleteFolder"><img src="icons/trash.svg" title="Delete" id="' + userFolders[i].folderID + '" onclick="deleteFolder(\'' + userFolders[i].folderID + '\',\'' + userFolders[i].folderName + '\')" />' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="flex1" id="folder' + userFolders[i].folderID + '"></div>' +
                        '</div>';

                    $("#folderHolder").append(newFolder);
                }

                loadFiles();

            } else {

                loadFiles();
                // $("#lp1").hide();
                // $("#headinglp1").hide();
            }
        }

    });

}


function loadFiles() {
    $.ajax({
        type: "POST",
        url: 'includes/loadUserFiles.inc.php',
        data: {
            loadFilesStart: filesStart,
            hubID: currentHubID
        },
        success: function(data) {
            filecheck = "";

            //If there is data
            if (data != "No results found") {

                $("#lp1").show();

                var userFiles = JSON.parse(data);

                for (var i = 0; i < userFiles.length; i++) {

                    var size = filesize(userFiles[i].fileSize);

                    var filetype = "";

                    var fullname = userFiles[i].filename;
                    var extension = fullname.substring(fullname.length, fullname.length - 3);
                    if (extension == "pdf" || extension == "PDF") {
                        filetype = "pdf";
                    }

                    var file = '<div class="fileOuter box flexContainer" id="file' + userFiles[i].fileID + '" draggable="true" ondragstart="drag(event)">' +
                        '<div class="file flex3">' +
                        ((filetype == "pdf") ? '<img src="icons/pdf-file.svg" class="filetype"/>' : '<img class="filetype" src="icons/picture1.svg"/>') +
                        '<p>' + userFiles[i].filename + '</p></div>' +
                        '<div class="flex1 fSize">' + size + '</div>' +
                        '<div class="optionsOuter flex1 flexContainer">' +
                        '<div class="fileOptions flex1 shareFile"><a href="#ex1" rel="modal:open"><img src="icons/add.svg" title="Share" onclick="shareFile(' + userFiles[i].fileID + ')"/></a></div>' +
                        '<div class="fileOptions flex1 loadFile"><img src="icons/preview.svg" title="Preview" onclick="loadSharedFile(\'' + userFiles[i].fileID + '\',\'' + userFiles[i].filename + '\')"/></div>' +
                        '<div class="fileOptions flex1"><img src="icons/download.svg" title="Download" onclick="downloadFile(' + userFiles[i].fileID + ')"/></div>' +
                        '<div class="fileOptions flex1 deleteFile"><img src="icons/trash.svg" title="Delete" id="' + userFiles[i].fileID + '" onclick="deleteFile(\'' + userFiles[i].fileID + '\',\'' + userFiles[i].filename + '\')"/></div>' +
                        //'<div class="fileOptions flex1 moreFile" onclick="showMore(' + userFiles[i].fileID + ')"><img src="icons/more.svg" title="More" id="' + userFiles[i].fileID + '"/></div>' +
                        //'<div class="moreDropdown" id="dd' + userFiles[i].fileID + '"><ul><li title="Download File" onclick="downloadFile(' + userFiles[i].fileID + ')">Download</li></ul></div>' +
                        //<li title="Replace File" onclick="replaceFile('+ userFiles[i].fileID +')">Replace</li>
                        '</div>';

                    if (userFiles[i].parentFolder == null) {
                        $("#fileHolder").append(file);
                    } else {
                        $("#folder" + userFiles[i].parentFolder).append(file);
                    }

                }
            } else {
                // $("#lp1").hide();
                // $("#headinglp1").hide();
            }
        }

    });
}


function deleteShared(fileID, fileName) {

    //Put the file into a variable to remove on success
    var fileFrontEnd = $("#file" + fileID);

    //Show the confirmation modal
    $.confirm({
        title: 'Delete File',
        content: 'Are you sure you want to remove </br>' + fileName + "?",
        buttons: {
            Delete: {
                text: 'Remove',
                btnClass: 'deleteConfirm',
                action: function() {
                    $.ajax({
                        type: "POST",
                        url: 'includes/deleteshared.inc.php',
                        data: {
                            deletesharedID: fileID
                        },
                        success: function(data) {

                            if (data == "file removed") {

                                //Remove file on front end to avoid reloading page
                                fileFrontEnd.remove();

                                var message = "Removed " + fileName;

                                showError(message);

                                if ($("#lp2").find(".file").length == 0) {
                                    $("#lp2").hide();
                                }

                            }

                        }

                    });
                }
            },
            cancel: function() {

            },
        }
    });

}


//Load files that has been shared with the user
function loadSharedFile(fileID, filename) {

    var shareID = fileID;
    var name = filename;

    $.ajax({
        type: "POST",
        url: 'includes/loadSharedFile.inc.php',
        data: {
            shareFile: shareID
        },
        success: function(data) {
            var boardID = data;

            //Set the heading of the file board as the chosen files name
            $("#boardName").html(name);

            //Clear the chat
            $(".RP2").empty();

            //Sets the message input ID to the board ID the messages need to be sent to
            $(".msgInput").attr("id", boardID);

            $.ajax({
                type: "POST",
                url: 'includes/loadmsgs.inc.php',
                data: {
                    currentBoardID: boardID
                },
                success: function(data) {

                    previewFile(shareID);

                    //If there is data
                    if (data != "no messages to load") {


                        //Messages found
                        messages = JSON.parse(data);

                        //Returns message text, first name, last name, userID, message date & board ID of the message.
                        for (var i = 0; i < messages.length; i++) {

                            var newMessage = '<div class="message box flexContainer" id="' + messages[i].msgID + '">' +
                                '<div class="flex1 msgname">' + messages[i].firstName + " " + messages[i].lastName + '</div>' +
                                '<div class="flex4 msgactual">' + messages[i].msgText + '</div>' +
                                '</div>'
                                // var newMsg = '<div class="message"><p>'+ messages[i].firstName + " " + messages[i].lastName + ": " + messages[i].msgText + '</p></div>';
                            $(".RP2").append(newMessage);

                        }
                    } else {

                        //No messages found
                        //console.log("Front End: No Messages Found!");
                    }
                }

            });
        }

    });
}


//Delete Folder

function deleteFolder(folderID, folderName) {


    //Put the file into a variable to remove on success
    var folderFrontEnd = $("#file" + folderID);
    var message = "Deleted " + folderName;

    //Show the confirmation modal
    $.confirm({
        title: 'Delete Folder',
        content: 'Are you sure you want to delete </br>' + folderName + "?",
        buttons: {
            Delete: {
                text: 'Delete',
                btnClass: 'deleteConfirm',
                action: function() {
                    $.ajax({
                        type: "POST",
                        url: 'includes/deleteFolder.inc.php',
                        data: {
                            dFolderID: folderName
                        },
                        success: function(data) {

                            //console.log(data);
                            //Remove file on front end to avoid reloading page
                            folderFrontEnd.remove();

                            showError(message);

                            if ($('#lp1').find(".file").length == 0) {
                                // $("#lp1").hide();
                            }
                        }
                    });
                }
            },
            cancel: function() {

            },
        }
    });
}


//DELETE FILE
var fileToDelete = "";

function deleteFile(fileID, fileName) {

    //Put the file into a variable to remove on success
    var fileFrontEnd = $("#file" + fileID);
    var message = "Deleted " + fileName;

    //Show the confirmation modal
    $.confirm({
        title: 'Delete File',
        content: 'Are you sure you want to delete </br>' + fileName + "?",
        buttons: {
            Delete: {
                text: 'Delete',
                btnClass: 'deleteConfirm',
                action: function() {
                    $.ajax({
                        type: "POST",
                        url: 'delete.php',
                        data: {
                            deleteID: fileID
                        },
                        success: function(data) {
                            //Remove file on front end to avoid reloading page
                            fileFrontEnd.remove();

                            showError(message);

                            if ($('#lp1').find(".file").length == 0) {
                                // $("#lp1").hide();
                            }
                        }
                    });
                }
            },
            cancel: function() {

            },
        }
    });
}


//Press enter on the chat input
// $(".msgInput").on('keyup', function (e) {
//     if (e.keyCode === 13) {
//         $(".msgButton").click();
//     }
// });


//LOAD FILE
var filetoLoad = "";
var boardID = "";

$(".loadFile").on("click", function() {

    //Find the name of the file through the parent
    filetoLoad = $(this).parent().parent().find(".file input").val();
    var fileselected = $(this).parent().parent();

    $(".RP2").empty();

    $.ajax({
        type: "POST",
        url: 'includes/loadboard.inc.php',
        data: {
            loadID: filetoLoad
        },
        dataType: "json",
        success: function(data) {


            //Gets the returned board ID
            boardID = data;

            //Sets the message input ID to the board ID the messages need to be sent to
            $(".msgInput").attr("id", boardID);

            $.ajax({
                type: "POST",
                url: 'includes/loadmsgs.inc.php',
                data: {
                    currentBoardID: boardID
                },
                success: function(data) {

                    //Clear all the other file backgrounds
                    // $(".fileOuter").css("background", "none");

                    //Set the heading of the file board as the chosen files name
                    $("#boardName").html("File Board: " + filetoLoad);

                    //Set the chosen file's background as 'active'
                    // fileselected.css("background", "linear-gradient(90deg, rgba(74,189,172,1) 0%, rgba(255,255,255,1) 100%)");


                    //If there is data
                    if (data != "no messages to load") {

                        //Messages found
                        messages = JSON.parse(data);

                        //Returns message text, first name, last name, userID, message date & board ID of the message.
                        for (var i = 0; i < messages.length; i++) {

                            var newMessage = '<div class="message box flexContainer" id="' + messages[i].msgID + '">' +
                                '<div class="flex1 msgname">' + messages[i].firstName + " " + messages[i].lastName + '</div>' +
                                '<div class="flex4 msgactual">' + messages[i].msgText + '</div>' +
                                '</div>'
                                // var newMsg = '<div class="message"><p>'+ messages[i].firstName + " " + messages[i].lastName + ": " + messages[i].msgText + '</p></div>';
                            $(".RP2").append(newMessage);

                        }
                    } else {

                        //No messages found
                        //console.log("Front End: No Messages Found!");
                    }



                }

            });
        }

    });

});

function openHub(hubID, hubName) {

    $("#fileSelect").show();
    $("#newFolder").show();

    //Clear the folder and file panels
    $("#folderHolder").empty();
    $("#fileHolder").empty();

    //Set the Current Hub ID to the one selected
    currentHubID = hubID;

    $("#dashTitle").hide();
    $("#dashTitle").text(hubName);
    $("#dashTitle").fadeIn('slow');

    //Load folders and files
    loadFolders();

}

//SHARE FILE
function shareFile(fileID) {

    //Set the email input ID to the file ID of the file, so it can be retrieved when submitting.
    $(".inviteEmail").attr("id", fileID);
}

$("#inviteNotice").click(function() {

    $('#ex4').modal();

});


//CLICK SUBMIT ON SEND INVITE MODAL
$("#sendInvite").on("click", function() {

    if ($(".inviteEmail").val() != "") {

        if (validateEmail($(".inviteEmail").val())) {

            //Get the File ID from the email input field in the modal
            var fileID = $(".inviteEmail").attr("id");

            //Get the email field value - inputted email from the user - THIS NEEDS TO BE VALIDATED ON FRONT END -
            var emailToSend = $(".inviteEmail").val();

            var message = "Invite sent to " + emailToSend;

            $.ajax({
                type: "POST",
                url: 'includes/shareFile.inc.php',
                data: {
                    sendInv: fileID,
                    emailTo: emailToSend
                },
                success: function(data) {
                    //Show the user that invited sent successfully
                    $(".inviteEmail").val('');

                    showSuccess(message);

                    $.modal.close();

                }
            });

        } else {
            //Inputted is not valid email
            showError("Please enter a valid email");
        }

    } else {
        //Invite input is empty
        showError("Please enter an email");
    }

});

function validateEmail(email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test(email);
}


//CLICK SEND NEXT TO MESSAGE FIELD - SEND MESSAGE
$("#sendMsg").click(function() {

    if ($(".msgInput").val() != "") {

        //Get the inputted message from the message input field
        var messageToSend = $(".msgInput").val();

        //Get the message board ID from the message input field
        var currentBoard = $(".msgInput").attr("id");

        $.ajax({
            type: "POST",
            url: 'includes/sendmsg.inc.php',
            data: {
                newMsg: messageToSend,
                loadedBoard: currentBoard
            },
            success: function(data) {
                //Create the new message on the front end if successfully added on back-end
                var newMsg = '<div class="message box flexContainer">' +
                    '<div class="flex1 msgname">' + data + '</div>' +
                    '<div class="flex4 msgactual">' + $(".msgInput").val() + '</div>' +
                    '</div>'
                    // var newMsg = '<div class="message"><p>'+ data + ": " + $(".msgInput").val() + '</p></div>';

                //Append the new message to the panel on the front end
                $(".RP2").append(newMsg);

                //Scroll the chat panel to the bottom
                $(".RP2").animate({
                    scrollTop: $('.RP2').prop("scrollHeight")
                }, 500);

                //Clear the message input field
                $(".msgInput").val("");
            }

        });
    }

});

//DOWNLOAD FILE
function downloadFile(data) {

    var fileID = data;

    $.ajax({
        type: "POST",
        url: 'includes/downloadfile.inc.php',
        data: {
            downloadID: fileID
        },
        dataType: 'json',
        success: function(data) {
            var downloadInfo = data[0];
            var downloadPath = "uploads/" + downloadInfo.userID + "/" + downloadInfo.filename;

            //Create a link to download the file
            var a = document.createElement('a');
            var url = downloadPath;
            a.href = url;
            a.download = downloadInfo.filename;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

            //Hide the dropdown menu
            currentDropdown.fadeOut();
        }
    });


}

//REPLACE FILE
function replaceFile(data) {

    //Create a link to download the file
    var a = document.createElement('a');
    var url = "#ex3";
    a.rel = "modal:open"
    a.href = url;
    document.body.append(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);

    currentDropdown.fadeOut();
}

function newHub() {
    //Create a link to open the create hub modal
    var a = document.createElement('a');
    var url = "#ex5";
    a.rel = "modal:open"
    a.href = url;
    document.body.append(a);
    a.click();
    a.remove();
    window.URL.revokeObjectURL(url);
}

function createHub() {

    var hubname = $(".hubName").val();

    $.ajax({
        type: "POST",
        url: 'includes/createHub.inc.php',
        dataType: 'json',
        data: {
            hubName: hubname
        },
        success: function(data) {

            //Close the modal
            $.modal.close();

            //Clear the field
            $(".hubName").val('');

            //Append the newly created hub
            var hublink = '<li class="hubA" id="' + data.hubID + '" onclick="openHub(\'' + data.hubID + '\',\'' + data.hubName + '\')">' + data.hubName + '</li>';
            $("#hubList").append(hublink);

            //Notify user
            showSuccess('Hub Created');

        }
    });

}

//SHOW FILE DETAILS
function fileDetails(data) {
    currentDropdown.fadeOut();
}


$(document).mouseup(function(e) {
    var container = $(".moreDropdown ul");

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.fadeOut();
    }
});


function openHistory() {

    var content = '<h1>Update History</h1>' +
        '<div class="post" id="post8">' +
        '<h1>23rd July 2020</h1>' +
        '<p>Create Folders - users can now create folders and add files to them</p>' +
        '<p>File size - file size now displayed on each file</p>' +
        '</div>' +
        '<div class="post" id="post7">' +
        '<h1>3rd July 2020</h1>' +
        '<p>Download Files - can now download each file from the file options dropdown.</p>' +
        '</div>' +
        '<div class="post" id="post6">' +
        '<h1>2nd July 2020</h1>' +
        '<p>Changed file upload to AJAX - no longer refreshes and adds file instantly.</p>' +
        '</div>' +
        '<div class="post" id="post5">' +
        '<h1>29th June 2020</h1>' +
        '<p>Redesigned login / signup pages.</p>' +
        '</div>' +
        '<div class="post" id="post4">' +
        '<h1>28th June 2020</h1>' +
        '<p>Redesigned main dashboard - previewing a file now popups instead of taking over third of screen.</p>' +
        '</div>' +
        '<div class="post" id="post3">' +
        '<h1>11th June 2020</h1>' +
        '<h2>Added:</h2>' +
        '<p>File preview - users can now preview PDF and Image files alongside comments.</p>' +
        '</div>' +
        '<div class="post" id="post2">' +
        '<h1>3rd June 2020</h1>' +
        '<h2>Added:</h2>' +
        '<p>Share files with other users.</p>' +
        '<p>Confirmation popup to reject invitations.</p>' +
        '<p>Users can now accept invitations.</p>' +
        '<p>View messages from other users in file chat.</p>' +
        '</div>' +
        '<div class="post" id="post1">' +
        '<h1>2nd June 2020</h1>' +
        '<p>Added the ability to invite other users to collaborate with you on a file.</p>' +
        '<p>Added the ability to reject invitations.</p>' +
        '<p>Added a invite popup to show outstanding invitations.</p>' +
        '<p>Added styling to messages.</p>' +
        '<p>Name of active file now shows above messages.</p>' +
        '<p>Changed input box to text area and disabled enter to submit.</p>' +
        '</div>';

    $("#ex2").empty();
    $("#ex2").append(content);
}


function showSuccess(message) {
    // Create an instance of Notyf
    var notyf = new Notyf();

    // Display a success notification
    notyf.success(message);
}


function showError(message) {
    // Create an instance of Notyf
    var notyf = new Notyf();

    // Display an error notification
    notyf.error(message);
}


function checkfornew() {

    if (checked) {
        var check = "1";

        var message = 'You have a new invite!';
        $.ajax({
            type: "POST",
            url: 'includes/invitecheck.inc.php',
            dataType: 'json',
            data: {
                invcheck: check
            },
            success: function(data) {

                if (data != "" && data != "No results found") {
                    if (data.length > invNumber) {
                        showError(message);
                        //console.log("invite number: " + invNumber + " new: " + data.length);
                        invNumber = data.length;

                    }
                }
            }
        });
    }

}


function createFolder() {

    var folderID = 1;

    $.ajax({
        type: "POST",
        url: 'includes/createFolder.inc.php',
        dataType: 'json',
        data: {
            folderCheck: folderID,
            hubID: currentHubID
        },
        success: function(data) {

            var newFolder = '<div class="fileOuter box flexContainer flexC folderContainer" id="file' + data.folderID + '" ondrop="drop(event)" ondragover="allowDrop(event)">' +
                '<div class="flex1 flexContainer flexR">' +
                '<div class="file flex3">' +
                '<img src="icons/folder.svg" class="filetype"/><p>' + data.folderName + '</p>' +
                '</div>' +
                '<div class="optionsOuter flex1 flexContainer">' +
                '<div class="fileOptions flex1 shareFolder"></div>' +
                '<div class="fileOptions flex1"></div>' +
                '<div class="fileOptions flex1"></div>' +
                '<div class="fileOptions flex1"></div>' +
                '<div class="fileOptions flex1"></div>' +
                '<div class="fileOptions flex1 deleteFolder"><img src="icons/trash.svg" title="Delete" id="' + data.folderID + '" onclick="deleteFolder(\'' + data.folderID + '\',\'' + data.folderName + '\')" />' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="flex1" id="folder' + data.folderID + '"></div>' +
                '</div>';

            $("#folderHolder").append(newFolder);
        }
    });
}

function allowDrop(ev) {
    ev.preventDefault();
}

var currentDrag = "";

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
    currentDrag = ev.target;
    $(".folderContainer").css("background-color", "#dcdcdc !important");
}

$(document).mousemove(function(e) {
    window.x = e.pageX;
    window.y = e.pageY;
});


function drop(ev) {
    ev.preventDefault();

    dragging = false;

    $(".folderContainer").css("background-color", "");

    //Find the folder container nearest to the dropped div
    var parent = $(ev.target).closest(".folderContainer");

    //Get the ID of the folder
    var id = parent.attr("id").replace("file", "");

    //Get the ID of the file
    var divID = $(currentDrag).attr("id");
    var fileID = divID.replace("file", "");

    if ($(currentDrag).hasClass("fileOuter")) {
        //Update the file parent ID on the database
        $.ajax({
            type: "POST",
            url: 'includes/moveFile.inc.php',
            dataType: 'text',
            data: {
                moveFile: fileID,
                moveFolder: id
            },
            success: function(data) {

                if (data == "file moved") {

                    //Add the ondrop to the new file within the folder
                    $(currentDrag).attr("ondrop", "drop(ev)");

                    //Append the file to the new folder
                    $("#folder" + id).append(currentDrag);

                    //Success Message
                    showSuccess("File Moved");
                }
            }
        });
    }



}