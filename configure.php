<?php
    define('DB_DSN','Driver={SQL Server};Server=SCZWSIST02\SQLEXPRESS;Database=files;Integrated Security=SSPI;Persist Security Info=False;');
    define('DB_SERVER', 'LPZWSCZ19\SQLEXPRESS');
    define('DB_SERVER_USERNAME', 'sa');
    define('DB_SERVER_PASSWORD', '123456789');
    define('DB_DATABASE', 'files');
    define('DB_PORT', '3306');
    define('SQL_LOG_PATH', "C:/logs.log");
    define('TABLE_ROW_VIEW','5');
    define('ODBC_DEFAULTLRL','4096');
    define('ODBC_MAXLRL','2000000');
    define('DB_DATETIME_FORMAT','Y-m-d H:i:s');
    define('LOG_DATETIME_FORMAT','Y-m-d H:i:s');
    define('ROOT_FOLDER','C:/inetpub/wwwroot/TestDoBrasil');
    define('MY_URI','http://172.27.52.92');
    define('UPGRADE_VERSION','2');
    define('SESSION_USER','zuser');
    define('ADMIN_USER','z1111111');
    define('ADMIN_PASSWORD','z1111111');

    define('SHOW_DATABASES','SELECT dbs.name AS Databases FROM sys.databases AS dbs;'); //MSSQL
    define('SHOW_TABLES','SELECT name mytable FROM sys.tables ORDER BY name;');//MSSQL
    define('DESC_TABLE','sp_columns');//MSSQL
    define('COLUMN_NAME','COLUMN_NAME');
    define('TYPE_NAME','TYPE_NAME');
    define('COLUMN_LENGTH','LENGTH');
    define('PRIMARY_KEY','ORDINAL_POSITION');
    define('PRIMARY_KEY_VALUE','1');
    define('CLASS_PATH','C:/inetpub/wwwroot/php2ee/class/');

?>
