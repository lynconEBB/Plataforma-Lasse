<?php
    $rootDir = "c:/Lasse-Project-Manager";
    $filesDirPath = "$rootDir/assets/files";
    $currentTime = date("d-m-Y_H-i-s");
    $generalDir = dir("$rootDir/backup/backups");
    $currentBackupDir = $generalDir->path."/".$currentTime;
    $backupsDir = [];

    while ($file = readdir($generalDir->handle)) {
        if ($file != "." && $file != "..") {
            $backupPath = $generalDir->path."/".$file;
            $backupsDir[$backupPath] =  filemtime($backupPath);
        }
    }

    deleteSpareBackups($backupsDir);
    backupFiles($filesDirPath,$currentBackupDir);
    dumpDatabase($currentBackupDir);
    zipBackup($currentBackupDir);

    function deleteSpareBackups(array $backupsDir): void
    {
        if (count($backupsDir) >= 10) {
            $oldestTime = min($backupsDir);
            $oldestBackup = array_search($oldestTime, $backupsDir);
            if (is_dir($oldestBackup)) {
                deleteDirectory($oldestBackup);
            } else {
                unlink($oldestBackup);
            }
            unset($backupsDir[$oldestBackup]);
            deleteSpareBackups($backupsDir);
        }
    }

    function backupFiles($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while( $file = readdir($dir) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    backupFiles($src.'/'.$file, $dst.'/'.$file);
                }
                else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }
        closedir($dir);
    }

    function deleteDirectory($path)
    {
        $files = array_diff(scandir($path),[".",".."]);
        if (count($files) > 0 ) {
            foreach ($files as $file) {
                if (is_dir($path . '/' . $file) ) {
                    deleteDirectory($path.'/'.$file);
                } else {
                    unlink($path.'/'.$file);
                }
            }
            rmdir($path);
        } else {
            rmdir($path);
        }
    }

    function dumpDatabase($currentBackupDir)
    {
        exec("mysqldump --defaults-extra-file=mysql.cnf  dbLPM > $currentBackupDir/database.sql ");
    }

    function zipBackup($dir)
    {
        $rootPath = realpath($dir);

        $zip = new ZipArchive();
        $zip->open($dir.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $filesToDelete = array();

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
                $filesToDelete[] = $filePath;
            }
        }

        $zip->close();

        foreach ($filesToDelete as $file)
        {
            unlink($file);
        }
        deleteDirectory($dir);
    }










