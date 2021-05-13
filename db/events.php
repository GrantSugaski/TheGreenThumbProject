<?php

function getEvents($db) {
    $sql = "select p.Organization, p.Description as OrgDescription, e.Name, e.Description, DATE_FORMAT(e.Time, '%W, %M %D %Y at %h:%i %p') as Time, e.Address, e.Produce from producers p join events e where p.ID = e.ID";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

?>