jQuery.ajax({
    url: "https://ionmedia.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/g6wb3c/b/c/3d70dff4c40bd20e976d5936642e2171/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs.js?locale=en-US&collectorId=1ba17699",
    type: "get",
    cache: true,
    dataType: "script"
});

window.ATL_JQ_PAGE_PROPS =  {
    "triggerFunction": function(showCollectorDialog) {
        jQuery("#bugReport").click(function(e) {
            e.preventDefault();
            showCollectorDialog();
        });
    }};
