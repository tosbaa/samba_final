<?php
    if (!is_array($data["shared_users"])) {
        dump($data["shared_users"]);
    }


    $userPermissions = [];
    if (is_array($data["shared_users"]["valid_users"])) {
        foreach ($data["shared_users"]["valid_users"] as $user => $permissions) {
            array_push($userPermissions,[
                "user_name" => $user,
                "permissions" => $permissions[0]
            ]);
        }
    }

    $groupPermissions = [];
    if (is_array($data["shared_users"]["valid_groups"])) {
        foreach ($data["shared_users"]["valid_groups"] as $group => $permissions) {
            array_push($groupPermissions, [
                "group_name" => $group,
                "permissions" => $permissions[0]
            ]);
        }
    }
?>

<h3>{{$data["shared_users"]["name"]}}</h3>
<p>
@include('modal-button',[
    "class" => "btn-primary",
    "target_id" => "addUser",
    "text" => "Kullanıcı ekle"
])


@include('modal-button', [
    "class" => "btn-primary",
    "target_id" => "addGroup",
    "text" => "Grup ekle"
])
<br><br>

<div class="row">
<div class="col-xs-6">
@include('table',[
    "value" => $userPermissions,
    "title" => [
        "Paylaşılan Kullanıcı", "ACL İzinleri"
    ],
    "display" => [
        "user_name", "permissions"
    ],
    "menu" => [
        "Sil" => [
            "target" => "deleteUser",
            "icon" => "fa-trash"
        ],
        "ACL Düzenle" => [
            "target" => 'aclUserShow',
            "icon" => "fa-edit"
        ],
    ],
    "noInitialize" => true
])
</div>


<div class="col-xs-6">
@include('table', [
    "value" => $groupPermissions,
    "title" => [
        "Grup", "ACL İzinleri"
    ],
    "display" => [
        "group_name", "permissions"
    ],
    "menu" => [
        "Sil" => [
            "target" => "deleteGroup",
            "icon" => "fa-trash"
        ],
        "ACL Düzenle" => [
            "target" => "aclGroupShow",
            "icon" => "fa-edit"
        ],
    ],
    "noInitialize" => true
])
</div>
</div>

@include('tree', [
    "data" => $data["shared_users"]["file_tree"]
])


@include('modal',[
    "id" => "addUser",
    "title" => "Kullanıcı Ekle",
    "url" => API("add_valid_user"),
    "next" => "reload",
    "inputs" => [
        "Kullanıcı adı" => "user_name:string",
        "Section adı:" .$data["shared_users"]["name"] => "section_name:hidden",
    ],
    "submit_text" => "Ekle"
])

@include('modal', [
    "id" => "deleteUser",
    "title" => "Kullanıcı Kaldır",
    "url" => API("remove_valid_user"),
    "next" => "reload",
    "inputs" => [
        "Section adı:" .$data["shared_users"]["name"] => "section_name:hidden",
        "Kullanıcı adı" => "user_name:hidden"
    ],
    "text" => "Kullanıcıyı kaldırmak istediğinizden emin misiniz ?",
    "submit_text" => "Kaldır"
])

@include('modal', [
    "id" => "addGroup",
    "title" => "Grup Ekle",
    "url" => API("add_valid_group"),
    "next" => "reload",
    "inputs" => [
        "Grup adı" => "group_name:string",
        "Section adı:" .$data["shared_users"]["name"] => "section_name:hidden",
    ],
    "submit_text" => "Ekle"
])

@include('modal', [
    "id" => "deleteGroup",
    "title" => "Grup Kaldır",
    "url" => API("remove_valid_group"),
    "next" => "reload",
    "inputs" => [
        "Section adı:" .$data["shared_users"]["name"] => "section_name:hidden",
        "Grup adı" => "group_name:hidden"
    ],
    "text" => "Grubu kaldırmak istediğinizden emin misiniz ?",
    "submit_text" => "Kaldır"
])


@include('modal', [
    "id" => "editUserAcl",
    "title" => "Acl Düzenle",
    "url" => API('setUserAcl'),
    "next" => "reload",
    "inputs" => [
        "r" => "r:checkbox",
        "w" => "w:checkbox",
        "x" => "x:checkbox",
        "Kullanıcı adı:" => "user_name:hidden",
        "Dosya:" .request("file_path") => "file_path:hidden",
    ],
    "submit_text" => "Kaydet"
])

@include('modal', [
    "id" => "editGroupAcl",
    "title" => "Acl Düzenle",
    "url" => API('setGroupAcl'),
    "next" => "reload",
    "inputs" => [
        "r" => "r:checkbox",
        "w" => "w:checkbox",
        "x" => "x:checkbox",
        "Grup adı:" => "group_name:hidden",
        "Dosya:" .request("file_path") => "file_path:hidden",
    ],
    "submit_text" => "Kaydet"

])


<script>
    function aclUserShow(line) {
        var permissions = line.querySelector('#permissions').innerHTML.split('');
        var user_name = line.querySelector('#user_name').innerHTML;
        $("#editUserAcl [name='user_name']").attr("value", user_name);
        if (permissions.length === 0) {
            $("input:checkbox").attr("checked", false);
        }
        for (var i = 0; i < permissions.length; i++) {
            if (permissions[i] !== '-') {
                permission = "'" + permissions[i] + "'";
                var checkboxName = '#editUserAcl [name=' + permission + ']';
                $(checkboxName).attr('checked', true);
            }
            else {
                permission = "'" + permissions[i] + "'";
                var checkboxName = '#editUserAcl [name=' + permission + ']';
                $(checkboxName).attr('checked', false);
            }
        }
        $('#editUserAcl').modal('show');
    }

    function aclGroupShow(line) {
        var permissions = line.querySelector('#permissions').innerHTML.split('');
        var user_name = line.querySelector('#group_name').innerHTML;
        $("#editGroupAcl [name='group_name']").attr("value", user_name);
        if (permissions.length === 0) {
            $("input:checkbox").attr("checked", false);
        }
        for (var i = 0; i < permissions.length; i++) {
            if (permissions[i] !== '-') {
                permission = "'" + permissions[i] + "'";
                var checkboxName = '#editGroupAcl [name=' + permission + ']';
                $(checkboxName).attr('checked', true);
            }
            else {
                permission = "'" + permissions[i] + "'";
                var checkboxName = '#editGroupAcl [name=' + permission + ']';
                $(checkboxName).attr('checked', false);
            }
        }
        $('#editGroupAcl').modal('show');
    }

</script>
