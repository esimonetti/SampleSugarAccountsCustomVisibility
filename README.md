# SampleSugarAccountsCustomVisibility
Custom Accounts visibility to hide flagged records

The customisation will create a new core indexed checkbox field on Accounts with unique name (c_hidden).
If the checkbox is flagged, it will hide the record for all non-admin users or non-admin users for Accounts (through their Role).

After the installation, run a system repair and Elastic re-index.

## Requirements
* Built and tested on Sugar Enterprise 7.9.2.0
* Tested on LAMP stack, with Elastic 1.7.5
