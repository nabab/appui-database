<!-- HTML Document -->

<div class="bbn-w-100">
  <request v-model="code"
           :databases="source.databases"
           :database="database"
           mode="write"/>
  <template v-for="item in codes">
    <component :is="$options.components.request" v-model="item.code"/>
  </template>
</div>


<script id="bbn-tpl-appui-database-console-request"
        type='text/x-template'>
<div class="bbn-w-100 bbn-vmargin">
  <div class="bbn-w-100 bbn-vpadding" v-if="mode === 'write'">
    <bbn-dropdown v-model="currentDatabase"
                  :source="databases"
                  :placeholder="_('Choose database')"/>
  </div>
  <div class="bbn-w-100 bbn-vpadding">
    <bbn-code v-model="currentValue"
              class="bbn-w-100"
              mode="sql"
              style="width:100%"
              ref="code"
              :autosize="true"
              :readonly="mode === 'read'"
              :scrollable="false"
              :fill="false">
    </bbn-code>
  </div>
  <div class="bbn-w-100 bbn-vpadding" v-if="mode === 'write'">
    <bbn-button @click="exec" icon="nf nf-cod-run_all">
      Run query
    </bbn-button>
  </div>
</div>

</script>