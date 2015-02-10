<?php


if (!defined("PDO::ATTR_DRIVER_NAME")) {
echo "unavailable";
}
elseif (defined("PDO::ATTR_DRIVER_NAME")) {
echo "PDO available";
}
?>
