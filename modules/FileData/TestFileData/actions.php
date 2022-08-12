<?php

Actions::on("routes", function() {
    FileData::append("thing/file.txt", "\nHello World");
});
