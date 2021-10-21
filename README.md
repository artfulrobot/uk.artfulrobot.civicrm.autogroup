# CiviCRM AutoGroup extension

This extension allows you to choose a set of groups which will be added to new
contacts if the logged in contact is also in that group.

e.g. If Staff member Wilma is in the group 'Region: Birmingham' and Wilma adds a
new contact, it can automatically add the new contact into 'Region: Birmingham'
group.

## Why this was created

If you are using Access Control Lists (ACLs) to restrict access based on groups,
e.g. a client of ours has a regional model whereby staff in different regions
only access contacts for that region, then it's annoyingly easy to add a contact
and immediately lose access to it because you forgot to add them into your
regional group.

But you may find it useful for other purposes, too.

## Install and configuration.

Install is pretty simple, nothing special here. If it's lucky enough to get
listed you may be able to do it with a click from within CiviCRM. Otherwise
download the zip file and unzip it in your extensions directory and then click
install on the extensions list page.

Configuration is done by `<yoursite>/civicrm/admin/autogroup` - you should find
a "Configure AutoGroup" link under the **Administer** menu. **Note that you need
'administer CiviCRM' permission to access the configuration.**

On that page you'll see a list of all your groups. Select the ones you want the
extension to auto-add. (To select more than one hold Ctrl key as you click them,
or for Mac users, it's probably the option key.) then press Save.

Notes:

- Only normal groups are listed. (i.e. not mailing groups and not access
  groups).

- If you don't select any, the extension does nothing.

- If you add a new group and you want it to auto-add to new contacts, you'll
  need to use the configure page again; new groups default NOT to be included.

## Ideas people, coding people

This has been created for a specific client's needs, but made as an extension as
it seemed to me to be a potentially common issue.

There are phpunit tests for the functionality.

Currently it's very simple, simplistic even. Please use the Issue Queue to
discuss any improvements. Please also note that a more general nuts-and-bolts
solution to your problem may be found in using CiviRules instead.

Enjoy :-)

## Version 1.1

This used to just work when adding Individuals, but now works for Households and
Organisations (Issue #1)
