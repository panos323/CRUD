$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

    $(".modal").on("shown.bs.modal", function() {
        removeLoader();
        autosizeTextArea();
    });

    $(".modal").on("hidden.bs.modal", function() {
        addLoader();
    });
    createUser();
    editUser();
    deleteUser();
    activateUser();
    showActive();
    editDetails();
    removeUserMessages();
    resetForm();
});

function removeUserMessages() {
    $("#userCreateModal").on("hidden.bs.modal", function() {
        if (!$("#userCreatedAlert").hasClass("d-none")) {
            $("#userCreatedAlert").addClass("d-none");
        }
    });
}

function editDetails() {
    let editMode = true;
    $(".editBtn").off("click.editdetails").on("click.editdetails", function() {
        if (editMode) {
            $(this).closest(".modal-body").find(":input").removeAttr("disabled");
            $(this).text("Save");
            editMode = false;
        } else {
            $(this).closest(".modal-body").find(":input").not('button').attr("disabled", true);
            $(this).text("Edit");
            editMode = true;
        }
    })
}

function showActive() {
    $(document).on("mouseover", ".users-list-group-item", function() {
        $(this).addClass("activeColor");
    }).on("mouseout", ".users-list-group-item", function() {
        $(this).removeClass("activeColor");
    })
}

function removeLoader() {
    $(".formInvisible").removeClass("invisible").fadeIn("slow", function() {
        $(".loaderModal").fadeOut("slow").addClass("d-none");    
    })
}

function addLoader() {
    $(".formInvisible").addClass("invisible").fadeIn("slow", function() {
        $(".loaderModal").fadeIn("fast").removeClass("d-none");    
    })
}

function autosizeTextArea() {
    var textarea = document.querySelectorAll('textarea');
    autosize(textarea);
    autosize.update(textarea);
}

function editUser() {
    $(".editBtn").on("click", function() {
        if ($(this).text() == "Save") {
            let dataID = $(this).data("id")
            let userDetails = $(this).closest(".modal-body").find("form").serialize() + "&param="+Number(dataID);
            $.ajax({
                type: "POST",
                url: "../modalsPHP/db/queries.php",
                data:{
                    userDetails: userDetails,
                    action: 'editUser'
                },
                success: function(data) {
                    console.log(data);
                },
                error: function(xhr,err) {
                    console.log(xhr,err);
                }
            })
        }  
    })
}

function deleteUser() {
    $(document).on("click", ".deleteBtn", function(e) {
        e.stopPropagation();
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be able to revert this",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
                let userId = $(this).data("id");
                let li = $(this).parent()
                $.ajax({
                    type: "POST",
                    url: "../modalsPHP/db/queries.php",
                    data:{
                        userId: userId,
                        action: 'deleteUser',
                    },
                    success: function(data) {
                        li.fadeOut('slow', function() {
                            $(this).find(".deleteBtn").first().tooltip('dispose');
                            $(this).remove();
                            if ($(".activateBtn").length > 0 && $(".deleteBtn").length > 0) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: 'Contact has been deleted.',
                                })
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: 'All contacts has been deleted.',
                                }).then(function(){ 
                                    location.reload();
                                })
                            }
                        })
                    },
                    error: function(xhr,err) {
                        console.log(xhr,err);
                    },
                    complete: function() {
                        $("#deletedUsersList").load(location.href + " #deletedUsersList");
                    }
                })
            }
          })
    })
}

function activateUser() {
    $(document).on("click", ".activateBtn", function(e) {
        e.stopPropagation();
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate user!'
          }).then((result) => {
            if (result.isConfirmed) {
                let userId = $(this).data("id");
                let li = $(this).parent()
                $.ajax({
                    type: "POST",
                    url: "../modalsPHP/db/queries.php",
                    data:{
                        userId: userId,
                        action: 'acticvateUser',
                    },
                    success: function(data) {
                        li.fadeOut('slow', function() {
                            $(this).find(".activateBtn").first().tooltip('dispose');
                            $(this).remove();
                            if ($(".activateBtn").length > 0 && $(".deleteBtn").length > 0) {
                                Swal.fire(
                                'Activated!',
                                'Contact has been activated.',
                                'success'
                                )
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Activated',
                                    text: 'All contacts has been activated.',
                                }).then(function(){ 
                                    location.reload();
                                })
                            }
                        })
                    },
                    error: function(xhr,err) {
                        console.log(xhr,err);
                    },
                    complete: function() {
                        $("#showUsersList").load(location.href + " #showUsersList");
                    }
                })
            }
          })
    })
}

function createUser() {
    $(".saveBtn").on("click", function() {
        let userDetails = $(this).closest(".modal-body").find("form").serialize();
        $("#displayErrors").html("");
        if (createUserValidation()) {
            $.ajax({
                type: "POST",
                url: "../modalsPHP/db/queries.php",
                data:{
                    userDetails: userDetails,
                    action: 'createUser'
                },
                success: function(data) {
                    console.log(data);
                    $("#userCreateModal").find("form")[0].reset();
                    $("#userCreatedAlert").removeClass("d-none");
                },
                error: function(xhr,err) {
                    console.log(xhr,err);
                },
                complete: function() {
                    $("#showUsersList").load(location.href + " #showUsersList");
                }
            })
        }
    }) 
}

function hasNumber(elem) {
    return /\d/.test(elem);
  }

function createUserValidation() {
    let errors=[];
    var reg = new RegExp('/\d/');

    //length errros
    if ($.trim($("#fNameUserCreate").val()).length < 3) {
        errors.push("First name should be valid");
    }
    if ($.trim($("#lNameUserCreate").val()).length < 3) {
        errors.push("Last name should be valid");
    }
    if ($.trim($("#phoneUserCreate").val()).length <= 9) {
        errors.push("Phone should contain at least 10numbers");
    }
    //type errors
    if (reg.test($("#fNameUserCreate").val())) {
        errors.push("First name should contains characters");
    }

    if (hasNumber($("#fNameUserCreate").val())) {
        errors.push("First name should contains characters");
    }
    
    if (hasNumber($("#lNameUserCreate").val())) {
        errors.push("Last name should contains characters");
    }
  
    if (isNaN($("#phoneUserCreate").val())) {
        errors.push("Phone should not contains characters");
    }
    //if not empty required fields
    if ($("#userAddressCreate").val()) {
        if ($.trim($("#userAddressCreate").val()).length < 3) {
            errors.push("Address should be valid");
        }
    }
    if ($("#userCommentsCreate").val()) {
        if ($.trim($("#userCommentsCreate").val()).length < 3) {
            errors.push("Comments should be valid");
        }
    }

    //display errors
    errors.forEach((err)=> {
        $("#displayErrors").append("<li class=\"text-danger\">"+err+"</li>");
    })

    if (typeof errors == 'undefined' || errors.length <= 0) {
        return true;
    }
    return false;
}

function resetForm() {
    $("button[name='reset-user-btn']").on("click", function() {
        $(this).closest(".modal-body").find("form")[0].reset();
        $("#displayErrors").html("");
    });
}