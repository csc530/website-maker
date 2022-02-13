create definer = Christofer1157052@`%` trigger `insert_creator-admin`
    after insert
    on websites
    for each row
    Insert INTO websites_admin values (New.name,NEW.creatorID,(SELECT email FROM creators WHERE ID = NEW.creatorID));

