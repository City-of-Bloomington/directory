<head>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/layouts/<?php echo $this->layout; ?>.css" />
	<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/screen-<?= VERSION; ?>.css" />
	<link rel="stylesheet" type="text/css" href="<?= BASE_URI; ?>/css/Font-Awesome/css/font-awesome.min.css" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700,600' rel='stylesheet' type='text/css'>
	<title><?php echo isset($this->title) ? self::escape($this->title) : APPLICATION_NAME; ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URI; ?>/css/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo BASE_URI; ?>/css/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo BASE_URI; ?>/css/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo BASE_URI; ?>/css/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="<?php echo BASE_URI; ?>/css/images/favicon/safari-pinned-tab.svg" color="#1e59ae">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
</head>
