<!-- HTML Document -->

<div :class="[componentClass, 'bbn-border bbn-radius bbn-spadding bbn-block']">
  <div class="bbn-w-100 bbn-bottom-spadding" bbn-if="(operation === 'UPDATE') && !sub">
    <bbn-button @click="toggleShown"
                :icon="shown ? 'nf nf-fa-eye_slash' : 'nf nf-fa-eye'"
                :notext="false"
                :label="shown ? _('Hide unchanged values') : _('Show all values')"/>
  </div>
  <component bbn-if="source.component"
             :is="source.component"
             bbn-bind="source.componentOptions"/>
  <div class="bbn-grid-fields" bbn-else>
    <template bbn-for="f in source.fields"
              bbn-if="f.value || f.componentOptions || f.fields?.[0]?.value">
      <div class="bbn-label"
           bbn-text="f.label || f.name"
           bbn-if="shown || ('old_value' in f)"
           :class="{'bbn-b bbn-u': 'old_value' in f}"></div>
      <div bbn-if="shown || ('old_value' in f)">
        <div bbn-if="f.old_value">
          <component bbn-if="f.old_value.component"
                    :is="f.old_value.component"
                    bbn-bind="f.old_value.componentOptions"
                    :source="oldValues"/>
          <div bbn-elseif="f.old_value.options?.source"
               bbn-text="bbn.fn.getField(f.old_value.options.source, f.old_value.options.sourceText || 'text', {[f.old_value.options.sourceValue || 'value']: f.old_value.fields[0].value})"/>
          <div bbn-elseif="(f.old_value.fields?.length === 1) && !f.old_value.fields[0].type"
              bbn-text="f.old_value.fields[0].value"/>
          <div bbn-elseif="(f.old_value.fields?.length === 1) && f.old_value.fields[0].type === 'text'"
              bbn-text="f.old_value.fields[0].value"/>
          <div bbn-elseif="(f.old_value.fields?.length === 1) && f.old_value.fields[0].type === 'date'"
              bbn-text="bbn.fn.fdate(f.old_value.fields[0].value)"/>
          <div bbn-elseif="(f.old_value.fields?.length === 1) && f.old_value.fields[0].type === 'number'"
              bbn-text="bbn.fn.format(f.old_value.fields[0].value)"/>
          <div bbn-elseif="(f.old_value.fields?.length === 1)"
              bbn-html="bbn.fn.data2Html(f.old_value.fields[0])"/>
          <appui-database-data-record bbn-elseif="f.old_value.table"
                                      :source="f.old_value"
                                      :sub="true"/>
          <div bbn-elseif="!f.old_value.value || ((typeof f.old_value.value === 'string') && ['[]', '{}'].includes(f.old_value.value.trim()))">-</div>
          <div bbn-elseif="!f.old_value.type"
              bbn-text="f.old_value.value"/>
          <div bbn-elseif="f.old_value.type === 'text'"
              bbn-text="f.old_value.value"/>
          <div bbn-elseif="f.old_value.type === 'date'"
              bbn-text="bbn.fn.fdate(f.old_value.value)"/>
          <div bbn-elseif="f.old_value.type === 'number'"
              bbn-text="bbn.fn.format(f.old_value.value)"/>
          <div bbn-elseif="f.old_value.type === 'json'"
              bbn-html="bbn.fn.data2Html(JSON.parse(f.old_value.value))"/>
          <div bbn-else
              bbn-html="bbn.fn.data2Html(f.old_value)"/>
          <div bbn-if="f.old_value" class="bbn-w-100 bbn-bottom-spadding">
            <i class="bbn-b nf nf-fa-long_arrow_down"/>
          </div>
        </div>

        <component bbn-if="f.component"
                   :is="f.component"
                   bbn-bind="f.componentOptions"
                   :source="values"/>
        <div bbn-elseif="f.options?.source"
              bbn-text="bbn.fn.getField(f.options.source, f.options.sourceText || 'text', {[f.options.sourceValue || 'value']: f.fields[0].value})"/>
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
                                    :source="f"
                                    :sub="true"/>
        <div bbn-elseif="!f.value || ((typeof f.value === 'string') && ['[]', '{}'].includes(f.value.trim()))">-</div>
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
      </div>
    </template>
  </div>
</div>