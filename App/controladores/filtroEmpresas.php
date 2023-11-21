<?php
    // Estas son las empresas que si tenemos en cuenta
    $empIdIncluir = [1, 2];

    $filtroEmpresa = "";

    if (!empty($empIdIncluir)) {
        $filtroEmpresa = "AND ve.EmpId IN (" . implode(",", $empIdIncluir) . ")";
    }
?>