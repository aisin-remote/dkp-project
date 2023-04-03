<?php
if ($action == "api_dashboard_dm") {
    $template["group"] = "Home";
    $template["menu"] = "Dashboard";
    $dies = new Dies();
    $class = new Home();
    $data_group = $dies->getDiesGroup();
    $data_dies = $dies->getListDies(null, 'A');
    $data_model = $class->getDiesModel();
    $dies_prod = $dies->getDiesProd1();

    if (!empty($data_dies)) {
        $i = 0;
        foreach ($data_dies as $row) {
            $data_dies[$i]["bg_color"] = "bg-light";
            if (floatval($row["stkrun"]) >= floatval($row["ewstk"])) {
                $data_dies[$i]["bg_color"] = "bg-yellow";
            }

            if (floatval($row["stkrun"]) >= 2000) {
                $data_dies[$i]["bg_color"] = "bg-danger";
            }

            if ($row["gstat"] == 'P') {
                $data_dies[$i]["bg_color"] = "bg-blink";
                foreach ($dies_prod as $dies) {
                    if ($dies["dies_id"] == $row["dies_id"]) {
                        $data_dies[$i]["bg_color"] = "bg-red-blink";
                    }
                }
            }
            if ($row["gstat"] == 'R') {
                $data_dies[$i]["bg_color"] = "bg-blink";
                foreach ($dies_prod as $dies) {
                    if ($dies["dies_id"] == $row["dies_id"]) {
                        $data_dies[$i]["bg_color"] = "bg-red-blink";
                    }
                }
            }
            if ($row["gstat"] == 'PC') {
                $data_dies[$i]["bg_color"] = "bg-blink";
                foreach ($dies_prod as $dies) {
                    if ($dies["dies_id"] == $row["dies_id"]) {
                        $data_dies[$i]["bg_color"] = "bg-red-blink";
                    }
                }
            }

            if ($row["iostat"] == 'Maker') {
                $data_dies[$i]["bg_color"] = "bg-amber";
            }
            $i++;
        }
    }

    $return["data_group"] = $data_group;
    $return["data_dies"] = $data_dies;
    $return["data_model"] = $data_model;

    echo json_encode($return);
}
