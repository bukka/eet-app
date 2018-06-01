# EET App

The EET App is an application for exporting sales data to the Czech financial authority
using EET service. More info about EET can be found on
[etrzby.cz](http://www.etrzby.cz/cs/english-version-609).

## Usage

Currently the only supported format for sending data is CSV. The CSV file needs to be
placed to the input directory that configured in the main configuration located in
`config/parameters.yml`. The console command needs to be executed to send the data.
Then the output file can be found in the output directory that can be also configured.
The configuration for the output and input directory as well as certificate locations
must be set `config/parameters.yml` file as noted above. The
[dist file](config/parameters.yml.dist) has the required structure and contain
settings for the sandbox environment by default.

The execution is done using a console command (`./console`) with action `csv:export`
followed by the input file basename.

### Example

This example shows how to export data in the sendbox environment. First we just copy
the dist file that has all settings for the EET sendbox environment already in it:

```
cp config/paramters.yml.dist config/parameters.yml
```

Then we add test input file to `csv/in/test.csv`:

```csv
id,dat_odesl,prvni_zadani,overeni,dic_popl,id_provoz,id_pokl,porad_cis,dat_trzby,celk_trzba,rezim,zakl_dan1,dan1,zakl_dan2,dan2,zakl_dan3,dan3
1,"10.5.2018 9:10:01",ano,"ano",CZ24222224,101,3,5862,"9.1.2017",100,0,83,17,80,60.5,70,30
```

And finally we run the console command:
```
./console csv:export test.csv
```

The resulted file with all info returned by service (FIK, BKP, PKP, error info if there is any error)
will be created in `csv/out/test.csv`.
