#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#include <string.h>
#include <mysql.h>

// NHL Mobile version.

void get_link(char *team_one, char *team_two, char *game_id);
void get_penalties(char *game_id);

int main(int argc, char *argv[]){

	// give the args names, that way if we change how it works we don't need to change the code, we can just change these.
	char *team_one = argv[2];
	char *team_two = argv[3];
	char *game_time = argv[4];
	char *game_id = argv[1]; 

	time_t t; // start time
	time_t ts=time(NULL); // now time

	struct tm* tms = localtime(&ts);
	struct tm now = *tms;
	struct tm tm = *tms;


	printf("Game %s vs. %s at %s with game id:%s\n",team_one,team_two,game_time,game_id);
	strptime(game_time,"%I:%M%n%p",&tm);
	tm.tm_sec=0;
	tm.tm_isdst = -1; // checks daylight savings time
	t = mktime(&tm); // sets up the time_t datastructure.


	printf("Start: hour: %d; minute: %d; second: %d\n",tm.tm_hour, tm.tm_min, tm.tm_sec);
	printf("Now: hour: %d; minute: %d; second: %d\n",now.tm_hour, now.tm_min, now.tm_sec);


	double dt;
	dt = difftime(t,ts);
	if(dt<0) {
		//dt+=24*60*60;
		dt=0;
	}
	printf("Seconds until game starts: %f\n",dt);

	// boom, sleep until the game starts, then grab the boxscore link.
	sleep((int)(dt+30));

	//get_link(team_one,team_two,game_id);

	get_penalties(game_id);


	return 0;
}

void get_penalties(char *game_id){

	// mysql stuff.
	MYSQL *conn;
	MYSQL_RES *res;
	MYSQL_ROW row;
	char *server = "localhost";
	char *user = "phil";
	char *password = "***********"; /* set me first */
	char *database = "ratetheref";
	conn = mysql_init(NULL);

	char run[128];
	char *com = "/usr/bin/php /var/www/lighttpd/ratetheref/fetch/fetch_boxscore_data.php \"%s\"";
	snprintf(run, strlen(com)+strlen(game_id), com, game_id);

	/* Connect to database */
	if (!mysql_real_connect(conn, server,user, password, database, 0, NULL, 0)) {

		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);

	}

	while(1){

		char query[256];
		char *select = "SELECT game_id FROM games WHERE done='TRUE' AND game_id=%s";
		snprintf(query, strlen(select)+strlen(game_id) , select, game_id);
			// dest str,  size , format str, args

		/* send SQL query */
		if (mysql_query(conn, query)) {

			fprintf(stderr, "%s\n", mysql_error(conn));
			exit(1);

		}

		res = mysql_use_result(conn);


		row = mysql_fetch_row(res);
		//if the game isn't done, keep updating.
		if(row==NULL){ 

			system(run);			
			sleep(30);

		}

		else {

			/* close connection */
			mysql_free_result(res);
			mysql_close(conn);
			return;

		}

	
	}



}
