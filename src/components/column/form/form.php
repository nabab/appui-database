<div class="appui-database-form-column bbn-padding">
  <!---<span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-if="option && (option.text !== option.code)"
            v-text="option.text"/>
      <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            v-text="source.name"/> -->
  <bbn-form :scrollable="false"
            :buttons="false"
            mode="small"
            ref="form"
            :confirm-leave="false"
            :source="source"
            @success="onSuccess">
    <div class="bbn-grid-fields bbn-c">

      <div class="bbn-grid-full bbn-m bbn-i bbn-c">
        <bbn-input class="bbn-wider bbn-bottom-space"
                   v-model="source.name"
                   :required="true"
                   @change="checkColumnsNames"
                   :placeholder="_('Column\'s name')"/>
      </div>

      <div class="bbn-grid-full bbn-m bbn-vspadded bbn-i bbn-c bbn-b">
        {{question}}
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

        <label ><?=_("Reference Table")?></label>
        <bbn-dropdown class="bbn-wider"
                      v-model="constraint"
                      :required="true"
                      :source="constraints"
                      :placeholder="_('Choose the reference')"/>

        <label ><?=_("On delete")?></label>
        <bbn-dropdown class="bbn-narrow"
                      v-model="source.delete"
                      :required="true"
                      :source="onDelete"/>

        <label ><?=_("On update")?></label>
        <bbn-dropdown class="bbn-narrow"
                      v-model="source.update"
                      :required="true"
                      :source="onUpdate"/>

        <label><?=_("Nullable")?></label>
        <div>
          <bbn-checkbox v-model="source.null"
                        :value="true"
                        :novalue="false"/>
        </div>

        <label><?=_("Default Value")?></label>
        <bbn-radio :source="defaultValueTypes"
                   v-model="defaultValueType"
                   :required="true"/>

        <div v-if="['defined', 'expression'].includes(defaultValueType)">
        </div>
        <div v-if="defaultValueType === 'defined'">
          <component :is="defaultComponent"
                     v-bind="defaultComponentOptions"
                     v-model="source.default"
                     :required="true"/>
        </div>
        <div v-else-if="defaultValueType === 'expression'">
          <bbn-input v-model="source.default"
                     :required="true"/>
        </div>

        <label><?=_("Indexes")?></label>
        <div>
          <bbn-radio :source="constraintIndexes"
                     v-model="source.index"/>
        </div>
      </template>

      <template v-else-if="radioType === 'free'">
        <label><?=_("Column's type")?></label>
        <div>
          <bbn-dropdown :source="colTypes"
                        v-model="source.type"
                        :required="true"
                        @change="resetAll"/>
        </div>

        <label v-if="isValue" ><?=_("Values")?></label>
        <div v-if="isValue">
          <bbn-values v-model="values"
                      :required="true"/>
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
          <bbn-numeric v-model="source.decimals"
                       :min="1"
                       :max="20"
                       :required="true"/>
        </div>

        <label v-if="isNumber" ><?=_("Unsigned")?></label>
        <div v-if="isNumber">
          <bbn-checkbox :value="0"
                        :novalue="1"
                        v-model="source.signed"/>
        </div>

        <label><?=_("Nullable")?></label>
        <div>
          <bbn-checkbox v-model="source.null"
                        :value="true"
                        :novalue="false"/>
        </div>

        <label><?=_("Default Value")?></label>
        <bbn-radio :source="defaultValueTypes"
                   v-model="defaultValueType" />

        <div v-if="['defined', 'expression'].includes(defaultValueType)">
        </div>
        <div v-if="defaultValueType === 'defined'">
          <component :is="defaultComponent"
                     v-bind="defaultComponentOptions"
                     v-model="source.default"
                     :required="true"/>
        </div>
        <div v-else-if="defaultValueType === 'expression'">
          <bbn-input v-model="source.default"
                     :required="true"/>
        </div>

        <label><?=_("Indexes")?></label>
        <div>
          <bbn-radio :source="indexes"
                     v-model="source.index"/>
        </div>

      </template>
    </div>
    <div class="bbn-c bbn-w-100 bbn-vpadded">
      <bbn-button :text="buttonTitle"
                  :action="change"
                  :disabled="!isFormValid"
                  class="bbn-right-margin"/>
      <bbn-button :text="_('Cancel')"
                  :action="cancel"/>
    </div>
  </bbn-form>
</div>
