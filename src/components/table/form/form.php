<div class="bbn-w-100"
     style="min-width: 50em">

  <!---<span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-if="option && (option.text !== option.code)"
            v-text="option.text"/>
      <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-text="source.name"/> -->
  <bbn-form :source="formSource">
    <div class="bbn-grid-fields bbn-padded">
      <label><?=_("Column's name")?></label>
      <div>
        <bbn-input v-model="formSource.name" />
      </div>

      <label><?=_("Column's type")?></label>
      <div>
        <bbn-dropdown :source="colTypes" v-model="formSource.type" />
      </div>

      <label v-if="isNumber || isChar"><?=_("Max length")?></label>
      <div v-if="isNumber || isChar">
        <bbn-numeric v-model="formSource.max_length" :min="1" :max="1000" />
      </div>

      <label v-if="types.decimal.includes(formSource.type)"><?=_("Precision")?></label>
      <div v-if="types.decimal.includes(formSource.type)">
        <bbn-numeric v-model="formSource.decimal" :min="1" :max="20" />
      </div>

      <label v-if="isNumber" ><?=_("Unsigned")?></label>
      <div v-if="isNumber">
        <bbn-checkbox :value="1" :novalue="0" v-model="formSource.unsigned" />
      </div>

      <label><?=_("Nullable")?></label>
      <div>
        <bbn-checkbox v-model="formSource.nullable" :value="1" :novalue="0"/>
      </div>

      <label><?=_("Default Value")?></label>
      <bbn-radio :source="defaultValues" v-model="defaultValue" />

      <div v-if="['defined', 'expression'].includes(defaultValue)">
      </div>
      <div v-if="defaultValue === 'defined'">
        <component :is="defaultComponent"
                   v-bind="defaultComponentOptions"
                   v-model="formSource.default_value"/>
      </div>
      <div v-if="defaultValue === 'expression'">
        <bbn-input></bbn-input>
      </div>

      <label><?=_("Indexes")?></label>
      <div>
        <bbn-radio :source="indexes" v-model="formSource.index" />
      </div>
    </div>
  </bbn-form>
</div>