<!-- HTML Document -->

<div class="bbn-border bbn-radius bbn-spadding bbn-block">
  <component bbn-if="source.component"
             :is="source.component"
             bbn-bind="source.componentOptions"/>
  <div class="bbn-grid-fields" bbn-else>
    <template bbn-for="f in source.fields"
              bbn-if="f.value || f.componentOptions || f.fields?.[0]?.value">
      <div class="bbn-label" bbn-text="f.label || f.name"></div>
      <component bbn-if="f.component"
                 :is="f.component"
                 bbn-bind="f.componentOptions"/>
      <div bbn-elseif="(f.fields?.length === 1) && !f.fields[0].type"
           bbn-text="f.fields[0].value"/>
      <div bbn-elseif="(f.fields?.length === 1) && f.fields[0].type === 'text'"
           bbn-text="f.fields[0].value"/>
      <div bbn-elseif="(f.fields?.length === 1) && f.fields[0].type === 'date'"
           bbn-text="bbn.fn.fdate(f.fields[0].value)"/>
      <div bbn-elseif="(f.fields?.length === 1) && f.fields[0].type === 'number'"
           bbn-text="bbn.fn.format(f.fields[0].value)"/>
      <div bbn-elseif="(f.fields?.length === 1)"
           bbn-html="bbn.fn.data2Html(f.fields[0])"/>
      <appui-database-data-record bbn-elseif="f.table"
                                  :source="f"/>
      <div bbn-elseif="!f.type"
           bbn-text="f.value"/>
      <div bbn-elseif="f.type === 'text'"
           bbn-text="f.value"/>
      <div bbn-elseif="f.type === 'date'"
           bbn-text="bbn.fn.fdate(f.value)"/>
      <div bbn-elseif="f.type === 'number'"
           bbn-text="bbn.fn.format(f.value)"/>
      <div bbn-elseif="f.type === 'json'"
           bbn-html="bbn.fn.data2Html(JSON.parse(f.value))"/>
      <div bbn-else
           bbn-html="bbn.fn.data2Html(f)"/>
    </template>
  </div>
</div>