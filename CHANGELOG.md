# 2.4.0
Add support for the institution specific number of tokens per identity config setting.

# 2.3.2
Bugfix release. Remove call to non existing SecondFactorType::getAvailableSecondFactorTypes

# Release notes from the RMT era

**VERSION 2  UPGRADE GUZZLE TO GUZZLE 6.  AS GUZZLE NO LONGER STRIPS TRAILING SLASHES IN VERSION 6, THE COMMAND API NEEDS TO BE RECONFIGURED BY CONSUMERS. THIS WARRANTS A MAJOR RELEASE.**

* Version 2.3 - This release supports token registration without email verification
  02/08/2018 16:34  2.3.1  Add additional institution config second factor validation
  01/19/2018 10:00  2.3.0  initial release

* Version 2.2 - This release exposes a new middleware api endpoint.
  * 20/11/2017 10:31  2.2.3  Do not provide file name extension in getFileName method of RaSecondFactorExportQuery
  * 17/11/2017 15:02  2.2.0  initial release

* Version 2.1 - Support requested-at date for verified second factors
  * 16/11/2017 08:28  2.1.0  initial release

* Version 2.0 - Upgrade Guzzle to Guzzle 6.  As Guzzle no longer strips trailing slashes in version 6, the Command API needs to be reconfigured by consumers. This warrants a major release.
  * 07/03/2017 15:10  2.0.0  initial release

**VERSION 1  RELEASE 1.0**

* Version 1.6 - Added support for searching for RA candidates with specific second factor types
  * 22/02/2017 16:55  1.6.0  initial release

* Version 1.5 - Added allowed second factor list
  * 14/02/2017 17:07  1.5.0  initial release

* Version 1.4 - Removed capabilities that are not provided by MW anymore
  * 08/08/2016 17:11  1.4.0  initial release

* Version 1.3 - New api capabilities
  * 03/08/2016 10:56  1.3.0  initial release

* Version 1.2 - RA Locations
  * 22/06/2016 16:14  1.2.2  Fixed 1.2.1 release
  * 22/06/2016 16:10  1.2.1  Shows RA locations instead of Has personal ra locations
  * 22/06/2016 11:16  1.2.0  initial release

* Version 1.1 - Add ProveU2fDevicePossessionCommand and documentNumber to RaSecondFactor
  * 09/06/2016 10:15  1.1.0  initial release

* Version 1.0 - Release 1.0
  * 19/06/2015 11:21  1.0.0  initial release

**VERSION 0  FIRST PILOT RELEASE**

* Version 0.3 - Release for second Pilot
  * 04/05/2015 13:55  0.3.0  initial release

* Version 0.2 - Pass registration authority with vetting command
  * 26/03/2015 15:09  0.2.0  initial release

* Version 0.1 - First pilot release
  * 26/03/2015 14:05  0.1.0  initial release
