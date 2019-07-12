<?php
    if(!is_array($data["get_samba_index"])){
        dd("Dosyalar Okunamadi");
    }

    $tabloVerileri = [];
    foreach ($data["get_samba_index"] as $name => $details) {
        array_push($tabloVerileri,[
            "name" => $name,
            "owner" => $details["owner"],
            "path" => $details["path"],
            "size" => $details["size"]
        ]);
    }

    $selectVerileri = [];
    foreach ($data["get_samba_index"] as $key => $value) {
        $selectVerileri[$key . ":" . $key] = [
            "-:-" => "section_name:hidden"
        ];
    }

?>
@include('modal-button',[
    "class" => "btn-primary",
    "target_id" => "addSection",
    "text" => "Yeni Paylaşım"
])<br><br>

@include("table",[
    "value" => $tabloVerileri,
    "title" => [
        "Adi", "Sahibi", "Path", "Boyutu"
    ],
    "display" => [
        "name", "owner", "path", "size"
    ],
    "onclick" => "showDetails",
    "menu" => [
            "Sil" => [
                "target" => "delete",
                "icon" => "fa-trash"
            ]
        ],
])

@include('modal', [
    "id" => "addSection",
    "title" => "Section ekle",
    "url" => API("add_section"),
    "next" => "reload",
    "inputs" => [
        "Section adı" => "section_name:string",
        "Path" => "file_path:string"
    ],
    "submit_text" => "Ekle"
])

@include('modal', [
    "id" => "delete",
    "title" => "Section Kaldır",
    "url" => API("delete_section"),
    "next" => "reload",
    "inputs" => [
        "Section adı:-" => "name:hidden",
    ],
    "text" => "Section silmek istediğinize emin misiniz? Bu işlem geri alınamayacaktır.",
    "submit_text" => "Sil"
])


<script>
    function showDetails(line) {
        var section_name = line.querySelector('#name').innerHTML;
        var file_path = line.querySelector('#path').innerHTML;
        location.href = '{{navigate("details")}}?section_name=' + section_name + '&file_path=' + file_path;
    }
</script>

