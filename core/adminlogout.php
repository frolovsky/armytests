<?php 
session_start();

unset($_SESSION['adminlog']);
unset($_SESSION['adminkaf']); 
unset($_SESSION['testsrc']); 
unset($_SESSION['error']); 

session_destroy();

