<div class="bbn-w-100"
     style="min-width: 50em">

  <!---<span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-if="option && (option.text !== option.code)"
            v-text="option.text"/>
      <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-text="source.name"/> -->
  <hr class="bbn-hr">
  <h3>
    <?=_("New column")?>
  </h3>
  <div class="bbn-grid-fields bbn-padded" >
    <label class="bbn-lg"><?=_("Column's name")?></label>
    <div class="bbn-lg">
      <bbn-input v-model="source.name" :required="true" @change="checkColumnsNames"/>
    </div>

    <div class="bbn-grid-full bbn-m bbn-vspadded">
      <?=_("What kind of column do you want to create ?")?>
    </div>

    <div class="bbn-grid-full bbn-m bbn-vspadded">
      <bbn-radio :source="[{
                          text: _('A predefined one'),
                          value: 'predefined'
                          },
                          {
                          text: _('A reference to another table'),
                          value: 'constraint'
                          },
                          {
                          text: _('Configure it yourself'),
                          value: 'free'
                          }]"
                 storage-full-name="appui-database-column-form-radio-type"
                 v-model="radioType"
                 ref="radioType"/>
    </div>

    <div class="bbn-grid-full bbn-m bbn-vspadded bbn-c"
         v-if="radioType === 'predefined'">
      <bbn-dropdown class="bbn-wider"
                    :source="predefinedOptions"
                    v-model="predefinedType"
                    :placeholder="_('Choose a predefined column type')"/>
    </div>

    <template v-else-if="radioType === 'constraint'" >

      <label><?=_("Nullable")?></label>
      <div>
        <bbn-checkbox v-model="source.null" :value="true" :novalue="false"/>
      </div>

      <bbn-dropdown
                    class="bbn-wider"
                    v-model="source.constraint"
                    :source="tables"
                    :placeholder="_('Choose the reference')"/>
      <label ><?=_("On delete")?></label>
      <bbn-dropdown
                    class="bbn-narrow"
                    v-model="source.delete"
                    :source="onDelete"
                    ></bbn-dropdown>
      <label ><?=_("On update")?></label>
      <bbn-dropdown
                    class="bbn-narrow"
                    v-model="source.update"
                    :source="onUpdate"></bbn-dropdown>

      <label><?=_("Default Value")?></label>
      <bbn-radio :source="defaultValueTypes" v-model="defaultValueType" :required="true"/>

      <div v-if="['defined', 'expression'].includes(defaultValueType)">
      </div>
      <div v-if="defaultValueType === 'defined'">
        <component :is="defaultComponent"
                   v-bind="defaultComponentOptions"
                   v-model="source.default"
                   :required="true"
                   />
      </div>
      <div v-else-if="defaultValueType === 'expression'">
        <bbn-input v-model="source.default"
                   :required="true"
                   />
      </div>
    </template>

    <template v-else-if="radioType === 'free'">
      <label><?=_("Column's type")?></label>
      <div>
        <bbn-dropdown :source="colTypes" v-model="source.type" :required="true" @change="resetAll"/>
      </div>

      <label v-if="isValue" ><?=_("Values")?></label>
      <div v-if="isValue">
        <bbn-values v-model="values" :required="true"/>
      </div>

      <label v-if="isNumber || isChar"><?=_("Max length")?></label>
      <div v-if="isNumber || isChar">
        <bbn-numeric v-model="source.maxlength"
                     :min="1"
                     :max="1000"
                     :required="true"/>
      </div>

      <label v-if="types.decimal.includes(source.type)"><?=_("Precision")?></label>
      <div v-if="types.decimal.includes(source.type)">
        <bbn-numeric v-model="source.decimal" :min="1" :max="20" :required="true"/>
      </div>

      <label v-if="isNumber" ><?=_("Unsigned")?></label>
      <div v-if="isNumber">
        <bbn-checkbox :value="0" :novalue="1" v-model="source.signed" />
      </div>

      <label><?=_("Nullable")?></label>
      <div>
        <bbn-checkbox v-model="source.null" :value="true" :novalue="false"/>
      </div>

      <label><?=_("Default Value")?></label>
      <bbn-radio :source="defaultValueTypes" v-model="defaultValueType" />

      <div v-if="['defined', 'expression'].includes(defaultValueType)">
      </div>
      <div v-if="defaultValueType === 'defined'">
        <component :is="defaultComponent"
                   v-bind="defaultComponentOptions"
                   v-model="source.default"
                   :required="true"
                   />
      </div>
      <div v-else-if="defaultValueType === 'expression'">
        <bbn-input v-model="source.default"
                   :required="true"
                   />
      </div>

      <label><?=_("Indexes")?></label>
      <div>
        <bbn-radio :source="indexes" v-model="source.index" />
      </div>

    </template>

    <label> </label>
    <div>
      <bbn-button @click="change" text="<?= _('Confirm') ?>" :disabled="!isFormValid"/>
      <bbn-button @click="cancel" text="<?= _('Cancel') ?>"/>
    </div>
  </div>
</div>