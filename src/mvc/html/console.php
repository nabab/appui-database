<!-- HTML Document -->

<div class="bbn-w-100">
  <template v-for="item in codes">
    <component :is="$options.components.request" v-model="item.code"/>
  </template>
  <request v-model="code"
           :databases="source.databases"
           :database="source.database"
           mode="write"/>
</div>


<script id="bbn-tpl-appui-database-console-request"
        type='text/x-template'>
<div class="bbn-w-90 bbn-margin bbn-padding bbn-radius bbn-bordered bbn-c">
  <div class="bbn-w-100 bbn-padding bbn-flex-width bbn-c" v-if="mode === 'write'">
    <!--bbn-dropdown v-model="currentLanguage"
                  :source="['MySQL', 'MariaDB']"
                  :placeholder="_('Choose Database type')"/>
    <bbn-dropdown v-model="currentHost"
                  :source="['clovis_dev@db.m3l.co']"
                  :placeholder="_('Choose host')"/-->
    <bbn-dropdown v-model="currentDatabase"
                  :source="databases"
                  :value="database"
                  :autobind="true"
                  />
  </div>
  <div class="bbn-w-100 bbn-padding">
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
    <bbn-button class="bbn-w-100" @click="exec" icon="nf nf-cod-run_all">
      Run query
  </bbn-button>
  </div>
  </div>
</script>
