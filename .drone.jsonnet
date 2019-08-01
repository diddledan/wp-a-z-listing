local mysqlver = "5.6";

local php5 = ["5.6"];
local php70 = ["7.0", "7.1"];
local php72 = ["7.2", "7.3"];
local phplatest = ["latest","nightly"];

local wp46 = ["4.6","4.7","4.8","4.9"];
local wp50 = ["5.0","5.1","5.2"];
local wplatest = ["latest","nightly"];

local services = [
	{
		name: "mysql",
		image: "mysql:" + mysqlver,
		environment: {
			"MYSQL_DATABASE": "wordpress_tests",
			"MYSQL_ROOT_PASSWORD": "mysql",
		},
	},
];

local Pipeline(phpver, wpver) = {
	kind: "pipeline",
	name: "wordpress-" + wpver + ":php-" + phpver,

	steps: [
		{
			name: "phpunit",
			image: "bowlhat/gitlab-php-runner:" + phpver,
			commands: [
				"apt-get clean",
				"apt-get -yqq update",
				"DEBIAN_FRONTEND=noninteractive apt-get -yqqf install zip unzip subversion mariadb-client libmariadb-dev --fix-missing",
				"docker-php-ext-enable mbstring mysqli pdo_mysql intl gd zip bz2",
				"bash bin/install-wp-tests.sh wordpress_tests root mysql mysql " + wpver + " true",
				"curl -sSLo phpunit.phar https://phar.phpunit.de/phpunit-5.phar && chmod +x phpunit.phar",
				"composer install",
				"./phpunit",
			],
		},
	],
	services: services,
};

[
	// wp4.* supports up to php-7.1
	Pipeline(phpver, wpver)
	for wpver in wp46 + wp50 + wplatest
	for phpver in php5 + php70
]+[
	// wp5+ supports all php versions
	Pipeline(phpver, wpver)
	for wpver in wp50 + wplatest
	for phpver in php5 + php70 + php72 + phplatest
]
