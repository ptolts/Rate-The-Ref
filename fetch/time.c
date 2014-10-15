#include <time.h>
#include <stdio.h>
#include <stdlib.h>
#include <mysql.h>
#include <string.h>
#include <sys/types.h>
#include <unistd.h>

void game_day(char *time);
int check_day(char *time_in);
void spawn(char *time);


int main(void){


	// time stuff.
	char time[8]="";

	while(1){


		if(check_day(time)==1){

			printf("Same day: %s\n",time);
		
		}
		else {

			printf("New day: %s\n",time);

			game_day(time);

			// here we spawn monitors for each game.
			spawn(time);

		}

		sleep(3600);

	}


	return 0;

}

//time function.
int check_day(char *time_in){

	// time stuff.
	struct tm *ptr;
	time_t lt;
	char new_time[8];

	lt = time(NULL);
	ptr = localtime(&lt);

	strftime(new_time, 10, "%m.%d.%y", ptr);

	if(strcmp(time_in,new_time)==0){
		return 1;
	}

	else {
		strncpy(time_in,new_time,8);
		return 0;
	}
	
	// end time stuff

}

void game_day(char *time){

	// mysql stuff.
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_ROW row;
	char *server = "localhost";
	char *user = "phil";
	char *password = "********"; /* set me first */
	char *database = "ratetheref";
	conn = mysql_init(NULL);

	/* Connect to database */
	if (!mysql_real_connect(conn, server,user, password, database, 0, NULL, 0)) {

		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);

	}


	// here we loop waiting for the new gameday to be updated.
	while(1){

		char query[256];
		char *select = "SELECT date FROM dates WHERE date='%s'";
		int len = snprintf(query, strlen(select)+strlen(time) , select, time);
				// dest str,  size , format str, args

		/* send SQL query */
		if (mysql_query(conn, query)) {

			fprintf(stderr, "%s\n", mysql_error(conn));
			exit(1);

		}

		res = mysql_use_result(conn);


		row = mysql_fetch_row(res);
		//if we haven't yet grabbed todays games, return false.
		if(row==NULL){

			//execute script
			system("/usr/bin/php /var/www/lighttpd/ratetheref/fetch/fetch_game_data.php");


		}

		else {

			/* close connection */
			mysql_free_result(res);
			mysql_close(conn);
			return;

		}

		// we sleep here for a while in case it didnt update.
		//sleep(600);


	} // jump back into the loop

}

void spawn(char *time){

	// mysql stuff.
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_ROW row;
	MYSQL_FIELD *field;
	int num_fields;
	int i;
	char *server = "localhost";
	char *user = "phil";
	char *password = "********"; /* set me first */
	char *database = "ratetheref";
	conn = mysql_init(NULL);

	/* Connect to database */
	if (!mysql_real_connect(conn, server,user, password, database, 0, NULL, 0)) {

		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);

	}


	char query[256];
	char *select = "SELECT game_id,team_one,team_two,game_time FROM games WHERE date='%s'";
	int len = snprintf(query, strlen(select)+strlen(time) , select, time);
			// dest str,  size , format str, args

	/* send SQL query */
	if (mysql_query(conn, query)) {

		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);

	}

	res = mysql_store_result(conn);
	num_fields = mysql_num_fields(res);

	while ((row = mysql_fetch_row(res)))	{

		// the row[i] corresponds to the order from the SELECT statement above.
		pid_t pid;
	

		/* Attempt to fork and check for errors */
		if( (pid=fork()) == -1){
			fprintf(stderr,"Fork error. Exiting.\n");  /* something went wrong */
			exit(1);        
		}

		if(!pid){

			if(execl("child","child",row[0],row[1],row[2],row[3],NULL) == -1){
				fprintf(stderr,"execl Error!");
				exit(1);
			}
			else {
				wait();
			}
		}
	
		else {

			printf("Ran Monitor one for %s vs %s\n",row[1],row[2]);

		}

	}


	/* close connection */
	mysql_free_result(res);
	mysql_close(conn);
	return;


}

