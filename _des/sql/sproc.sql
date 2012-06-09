DELIMITER $$

DROP PROCEDURE IF EXISTS assign_user_to_group $$
CREATE PROCEDURE assign_user_to_group(
	IN p_id_user INT,
	IN p_id_group INT
)
BEGIN
	INSERT INTO
		user_groups
	(
		id_user,
		id_group
	)
	VALUES(
		p_id_user,
		p_id_group
	);
END;
$$

DROP PROCEDURE IF EXISTS unassign_user_from_group $$
CREATE PROCEDURE unassign_user_from_group(
	IN p_id_user INT,
	IN p_id_group INT
)
BEGIN
	DELETE FROM
		user_groups
	WHERE
		id_user = p_id_user AND
		id_group = p_id_group;
END;
$$

DROP PROCEDURE IF EXISTS add_debt $$
CREATE PROCEDURE add_debt(
	IN p_id_user INT,
	IN p_other_user TEXT,
	IN p_descr TEXT,
	IN p_amount INT,
	IN p_creator TEXT,
	IN p_dir VARCHAR(16),
	IN p_is_payback BOOL,
	IN p_date_real DATE	
)
BEGIN
	DECLARE id_other_user INT;
	DECLARE id_user_src_v INT;
	DECLARE id_user_dst_v INT;
	SELECT
		id
	INTO
		id_other_user
	FROM
		users
	WHERE
		username = p_other_user OR
		email = p_other_user OR
		id = p_other_user;
	IF (ISNULL(id_other_user)) THEN
		SELECT 2 AS res;
	ELSE
		IF (id_other_user = p_id_user) THEN
			SELECT 3 AS res;
		ELSE
			IF (p_dir = 'iowethem') THEN
				SET id_user_src_v = p_id_user;
				SET id_user_dst_v = id_other_user;
			ELSE
				SET id_user_src_v = id_other_user;
				SET id_user_dst_v = p_id_user;
			END IF;
			INSERT INTO
				debts
			(
				id_user_src,
				id_user_dst,
				creator,
				amount,
				descr,
				is_payback,
				is_confirmed,
				date_real,
				date_creation
			)
			VALUES(
				id_user_src_v,
				id_user_dst_v,
				p_creator,
				p_amount,
				p_descr,
				p_is_payback,
				FALSE,
				p_date_real,
				NOW()
			);
			SELECT 1 AS res;
		END IF;
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS confirm_debt $$
CREATE PROCEDURE confirm_debt(
	IN p_id_user INT,
	IN p_id_debt INT
)
BEGIN
	DECLARE z BOOLEAN;
	SELECT
		(id_user_src = p_id_user AND creator <> 'src' OR
		id_user_dst = p_id_user AND creator <> 'dst')
	INTO
		z
	FROM
		debts
	WHERE
		id = p_id_debt AND
		is_confirmed = FALSE;
	IF (z = TRUE) THEN
		UPDATE
			debts
		SET
			is_confirmed = TRUE
		WHERE
			id = p_id_debt;
		SELECT 1 AS res;
	ELSE
		SELECT 0 AS res;
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS invalidate_debt $$
CREATE PROCEDURE invalidate_debt(
	IN p_id_user INT,
	IN p_id_debt INT
)
BEGIN
	DECLARE z BOOLEAN;
	SELECT
		(id_user_src = p_id_user AND creator <> 'src' OR
		id_user_dst = p_id_user AND creator <> 'dst')
	INTO
		z
	FROM
		debts
	WHERE
		id = p_id_debt AND
		is_confirmed = FALSE;
	IF (z = TRUE) THEN
		DELETE FROM
			debts
		WHERE
			id = p_id_debt;
		SELECT 1 AS res;
	ELSE
		SELECT 0 AS res;
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS update_user_active $$
CREATE PROCEDURE update_user_active(
	IN p_id_user INT,
	IN p_state BOOLEAN
)
BEGIN
	UPDATE
		users
	SET
		is_active = p_state
	WHERE
		id = p_id_user;
END;
$$

DROP PROCEDURE IF EXISTS update_user_admin $$
CREATE PROCEDURE update_user_admin(
	IN p_id_user INT,
	IN p_state BOOLEAN
)
BEGIN
	IF (p_state = TRUE) THEN
		CALL assign_user_to_group(p_id_user, 3);
	ELSE
		CALL unassign_user_from_group(p_id_user, 3);
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS delete_user $$
CREATE PROCEDURE delete_user(
	IN p_id_user INT
)
BEGIN
	DELETE FROM
		debts
	WHERE
		id_user_src = p_id_user OR
		id_user_dst = p_id_user;
	DELETE FROM
		user_groups
	WHERE
		id_user = p_id_user;
	DELETE FROM
		users
	WHERE
		id = p_id_user;
END;
$$

DROP PROCEDURE IF EXISTS add_user $$
CREATE PROCEDURE add_user(
	IN p_first_name TEXT,
	IN p_last_name TEXT,
	IN p_email TEXT,
	IN p_birthday DATE,
	IN p_gender VARCHAR(16),
	IN p_id_locale INT,
	IN p_username VARCHAR(30),
	IN p_sha1_passwd VARCHAR(40)
)
BEGIN
	DECLARE id INT;
	INSERT INTO
		users
	(
		first_name,
		last_name,
		email,
		birthday,
		gender,
		id_locale,
		username,
		passwd,
		is_active,
		date_creation,
		theme
	)
	VALUES(
		p_first_name,
		p_last_name,
		p_email,
		p_birthday,
		p_gender,
		p_id_locale,
		p_username,
		p_sha1_passwd,
		TRUE,
		NOW(),
		'leaves'
	);
	SELECT
		LAST_INSERT_ID()
	INTO
		id;
	IF (id > 0) THEN
		INSERT INTO
			user_groups
		(
			id_user,
			id_group
		)
		VALUES(
			id,
			2
		);
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS update_user $$
CREATE PROCEDURE update_user(
	IN p_id_user INT,
	IN p_first_name TEXT,
	IN p_last_name TEXT,
	IN p_email TEXT,
	IN p_birthday DATE,
	IN p_gender VARCHAR(16),
	IN p_username VARCHAR(30),
	IN p_sha1_passwd VARCHAR(40),
	IN p_update_passwd BOOLEAN,
	IN p_theme VARCHAR(32)
)
BEGIN
	UPDATE
		users
	SET
		first_name = p_first_name,
		last_name = p_last_name,
		email = p_email,
		birthday = p_birthday,
		gender = p_gender,
		username = p_username,
		passwd = IF(
			p_update_passwd,
			p_sha1_passwd,
			passwd
		),
		theme = p_theme
	WHERE
		id = p_id_user;
END;
$$

DROP PROCEDURE IF EXISTS add_user_fav $$
CREATE PROCEDURE add_user_fav(
	IN p_id_user INT,
	IN p_fav_username TEXT
)
BEGIN
	DECLARE id_other_user INT;
	SELECT
		id
	INTO
		id_other_user
	FROM
		users
	WHERE
		username = p_fav_username;
	IF (ISNULL(id_other_user)) THEN
		SELECT 2 AS res;
	ELSE
		IF (id_other_user = p_id_user) THEN
			SELECT 3 AS res;
		ELSE
			INSERT INTO
				favs
			(
				id_user,
				id_user_fav
			)
			VALUES(
				p_id_user,
				id_other_user
			);
			SELECT 1 AS res;
		END IF;
	END IF;
END;
$$

DROP PROCEDURE IF EXISTS delete_user_fav $$
CREATE PROCEDURE delete_user_fav(
	IN p_id_user INT,
	IN p_id_user_fav INT
)
BEGIN
	DELETE FROM
		favs
	WHERE
		id_user = p_id_user AND
		id_user_fav = p_id_user_fav;
END;
$$

DROP PROCEDURE IF EXISTS delete_all_user_favs $$
CREATE PROCEDURE delete_all_user_favs(
	IN p_id_user INT,
	IN p_id_user_fav INT
)
BEGIN
	DELETE FROM
		favs
	WHERE
		id_user = p_id_user;
END;
$$

DELIMITER ;

DROP VIEW IF EXISTS vu_debts_details;
CREATE VIEW vu_debts_details AS
SELECT
	debts.id AS id_debt,
	debts.id_user_src AS id_user_src,
	debts.id_user_dst AS id_user_dst,
	debts.creator AS creator,
	debts.amount AS amount,
	debts.descr AS descr,
	debts.is_payback AS is_payback,
	debts.is_confirmed AS is_confirmed,
	debts.date_real AS date_real,
	debts.date_creation AS date_creation,
	u_dst.first_name AS dst_first_name,
	u_dst.last_name AS dst_last_name,
	u_dst.username AS dst_username,
	CONCAT(u_dst.first_name, ' ', u_dst.last_name) AS dst_full_name,
	u_src.first_name AS src_first_name,
	u_src.last_name AS src_last_name,
	u_src.username AS src_username,
	CONCAT(u_src.first_name, ' ', u_src.last_name) AS src_full_name
FROM
	debts
	LEFT JOIN users AS u_src ON (u_src.id = debts.id_user_src)
	LEFT JOIN users AS u_dst ON (u_dst.id = debts.id_user_dst);

DROP VIEW IF EXISTS vu_active_users;
CREATE VIEW vu_active_users AS
SELECT
	users.id AS id,
	users.last_name AS last_name,
	users.first_name AS first_name,
	CONCAT(users.first_name, ' ', users.last_name) AS full_name,
	users.username AS username,
	users.avatar AS avatar
FROM
	users
WHERE
	users.is_active = TRUE;

DROP VIEW IF EXISTS vu_user_favs;
CREATE VIEW vu_user_favs AS
SELECT
	favs.id_user AS id_user,
	favs.id_user_fav AS id_user_fav,
	users.username AS username,
	users.first_name AS first_name,
	users.last_name AS last_name,
	users.avatar AS avatar,
	CONCAT(users.first_name, ' ', users.last_name) AS dst_full_name
FROM
	users,
	favs
WHERE
	users.id = favs.id_user_fav AND
	users.is_active = TRUE;

DROP VIEW IF EXISTS vu_users_locales;
CREATE VIEW vu_users_locales AS
SELECT
	users.id AS id,
	users.username AS username,
	CONCAT(users.first_name, ' ', users.last_name) AS full_name,
	locales.name AS locale
FROM
	users
	LEFT JOIN locales ON (users.id_locale = locales.id)
ORDER BY
	users.username;
