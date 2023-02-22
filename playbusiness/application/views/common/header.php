<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title><?php echo $MetaTitle; ?></title>
        <meta name="description" content="<?php if (isset($MetaDescription)) : echo $MetaDescription;
endif; ?>" />
        <meta name="keywords" content="<?php if (isset($MetaKeyWords)) : echo $MetaKeyWords;
endif; ?>" />
        <meta name="robots" content="<?php if (isset($MetaRobots)) : echo $MetaRobots;
endif; ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=0.625, user-scalable=yes" />

		<link rel="stylesheet" type="text/css" href="/css/login_new.css">

<style>

.btn-group .button {
  background-color: #4CAF50; /* Green */
  border: 1px solid green;
  color: white;
  padding: 2px 2px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 18px;
  font-family:arial;
  cursor: pointer;
  float: left;
  margin-left: 2px; 
}

.btn-group .button:not(:last-child) {
  border-right: none; /* Prevent double borders */
}

.btn-group .button:hover {
  background-color: #3e8e41;
}

.button {
  display: inline-block;
  border-radius: 4px;
  background-color: #f4511e;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 28px;
  padding: 20px;
  width: 50px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
}
.button a {
  text-decoration;none;
 
}
.button span {
	width:90px;
	cursor: pointer;
	display: inline-block;
	position: relative;
	transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}
.box {
  position: relative;
  
}

.box select {
  background-color: #0563af;
  color: white;
  padding: 12px;
  width: 250px;
  border: none;
  font-size: 20px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
  -webkit-appearance: button;
  appearance: button;
  outline: none;
}

.box::before {
  content: "\f13a";
  font-family: Arial;
  position: absolute;
  top: 0;
  right: 0;
  width: 20%;
  height: 100%;
  text-align: center;
  font-size: 28px;
  line-height: 45px;
  color: rgba(255, 255, 255, 0.5);
  background-color: rgba(255, 255, 255, 0.1);
  pointer-events: none;
}

.box:hover::before {
  color: rgba(255, 255, 255, 0.6);
  background-color: rgba(255, 255, 255, 0.2);
}

.box select option {
  padding: 30px;
}
</style>
</head>
<body class="busi_home_main-body-css">
