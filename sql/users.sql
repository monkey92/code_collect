create table `users` (
	`id` int unsigned not null auto_increment,
	`name` varchar(64),
	`nickname` varchar(64),
	`sex` int,
	`email` varchar(64),
	`password` varchar(255),
	`weixin_id` varchar(64),
	`qq`		varchar(64),
	`mobile`	varchar(64),
	`identity_id` varchar(64),
	`remember_token` varchar(255),
	`deleted_at` datetime default null,
	`created_at` timestamp default current_timestamp,
	`updated_at` timestamp not null default current_timestamp  on update current_timestamp,
	primary key (`id`),
	unique key (`email`),
	unique key (`mobile`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;
