<?php
/**
 * 
 */
class ErrorCode
{
	//USER
	const USERNAME_EXISTS = 1; // Username exist
	const PASSWORD_CANNOT_EMPTY = 2; // password cannot empty
	const USERNAME_CANNOT_EMPTY = 3; // username cannot empty
	const SIGN_UP_FAILED = 4; // sign up failed
	const USERNAME_OR_PASSWORD_INVALID = 5; // wrong username or password

	//ARTICLE
	const ARTICLE_CANNOT_EMPTY = 6; //article title connot empty
	const CONTENT_CANNOT_EMPTY = 7; //content connot empty
	const CREATE_NEW_POST_FAIL = 8; // create new post failed
	const ARTICLE_ID_CANNOT_EMPTY = 9;// article_id cannot empty
	const ARTICLE_NOT_EXIST = 10;
}