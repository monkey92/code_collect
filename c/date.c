#include <stdio.h>

static char daytab[2][13] = {
	{0,31,28,31,40,31,30,31,31,30,31,30,31},
	{0,31,29,31,40,31,30,31,31,30,31,30,31}
};
//给定几月几号求一年当中的第几天
int day_of_year(int year,int month,int day){
	int i , leap;
	leap = year % 4 == 0 && year % 100 != 0 || year % 400 == 0;
	for(i = 1; i < month;i++){
		day += daytab[leap][i];
	}
	return day;
}
//给定一年当中的第几天求几月几号
void month_day(int year,int year_day,int *pmonth,int *pday){
	int i , leap;
	leap = year % 4 == 0 && year % 100 != 0 || year % 400 == 0;
	for(i=1;year_day>daytab[leap][i];i++){
		year_day -= daytab[leap][i];
	}
	*pmonth = i;
	*pday = year_day;
}





