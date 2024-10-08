
    CREATE TABLE IF NOT EXISTS url (
        id INT( 11 ) AUTO_INCREMENT ,
        url VARCHAR( 255 ) NOT NULL DEFAULT '' ,
        hash VARCHAR( 14 ) NOT NULL DEFAULT '' ,
        created_date DATETIME NOT NULL,
        expiration_date DATETIME,
        sent TINYINT(1) DEFAULT 0,
        PRIMARY KEY ( id )
    ) ;
