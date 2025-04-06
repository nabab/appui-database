<!-- HTML Document -->

<div class="appui-database-console bbn-overlay bbn-flex-height">
  <div class="bbn-flex-fill">
    <bbn-code bbn-model="code" mode="sql"/>
  </div>
  <div class="bbn-xspadding">
    <bbn-button @click="exec" icon="nf nf-cod-run_all">
      Run query
    </bbn-button>
  </div>
  <div class="bbn-w-100 bbn-h-50">
    <bbn-json-editor bbn-model="result" class="bbn-flex-fill"/>
  </div>
</div>
