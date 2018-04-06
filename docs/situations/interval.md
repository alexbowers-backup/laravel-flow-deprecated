## Interval

If you want to check whether certain conditions are true multiple times before you schedule and run a job
then you want to use Flow Interval

An example would be monitoring server memory or CPU usage in a monitoring service.

If the monitor indicates that your server has high CPU usage, you don't want to immediately
fire an email if it doesn't last more than say, 5 minutes.

Example

>> SustainedServerMemoryUsageAlertFlow

When a `ServerUsingTooMuchMemory` event is fired
I want to:
  - immediately
before:
  - Bail if an existing SustainedServerMemoryUsageAlertFlow exists
repeat:
  - Every 1 minute for 5 times
check:
  - Is the server memory still above the acceptable threshold
do:
  - Send the alert to the server administrator
