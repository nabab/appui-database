<div class="appui-database-form-column bbn-padding">
  <!---<span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            bbn-if="option && (option.text !== option.code)"
            bbn-text="option.text"/>
      <span class="bbn-m bbn-b bbn-space-right bbn-iblock"
            bbn-text="source.name"/> -->
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
                   bbn-model="source.name"
                   :required="true"
                   @change="checkColumnsNames"
                   :placeholder="_('Column\'s name')"/>
      </div>

      <div class="bbn-grid-full bbn-m bbn-vspadding bbn-i bbn-c bbn-b">
        {{question}}
      </div>

      <div class="bbn-grid-full bbn-m bbn-vspadding">
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
                   bbn-model="radioType"
                   ref="radioType"/>
      </div>

      <div class="bbn-grid-full bbn-m bbn-vspadding bbn-c"
           bbn-if="radioType === 'predefined'">
        <bbn-dropdown class="bbn-wider"
                      :source="predefinedOptions"
                      bbn-model="predefinedType"
                      :placeholder="_('Choose a predefined column type')"/>
      </div>

      <template bbn-else-if="radioType === 'constraint'" >

        <label ><?= _("Reference Table") ?></label>
        <bbn-dropdown class="bbn-wider"
                      bbn-model="constraint"
                      :required="true"
                      :source="constraints"
                      :placeholder="_('Choose the reference')"/>

        <label ><?= _("On delete") ?></label>
        <bbn-dropdown class="bbn-narrow"
                      bbn-model="source.delete"
                      :required="true"
                      :source="onDelete"/>

        <label ><?= _("On update") ?></label>
        <bbn-dropdown class="bbn-narrow"
                      bbn-model="source.update"
                      :required="true"
                      :source="onUpdate"/>

        <label><?= _("Nullable") ?></label>
        <div>
          <bbn-checkbox bbn-model="source.null"
                        :value="true"
                        :novalue="false"/>
        </div>

        <label><?= _("Default Value") ?></label>
        <bbn-radio :source="defaultValueTypes"
                   bbn-model="defaultValueType"
                   :required="true"/>

        <div bbn-if="['defined', 'expression'].includes(defaultValueType)">
        </div>
        <div bbn-if="defaultValueType === 'defined'">
          <component :is="defaultComponent"
                     bbn-bind="defaultComponentOptions"
                     bbn-model="source.default"
                     :required="true"/>
        </div>
        <div bbn-else-if="defaultValueType === 'expression'">
          <bbn-input bbn-model="source.default"
                     :required="true"/>
        </div>

        <label><?= _("Indexes") ?></label>
        <div>
          <bbn-radio :source="constraintIndexes"
                     bbn-model="source.index"/>
        </div>
      </template>

      <template bbn-else-if="radioType === 'free'">
        <label><?= _("Column's type") ?></label>
        <div>
          <bbn-dropdown :source="colTypes"
                        bbn-model="source.type"
                        :required="true"
                        @change="resetAll"/>
        </div>

        <label bbn-if="isValue" ><?= _("Values") ?></label>
        <div bbn-if="isValue">
          <bbn-values bbn-model="values"
                      :required="true"/>
        </div>

        <label bbn-if="isNumber || isChar"><?= _("Max length") ?></label>
        <div bbn-if="isNumber || isChar">
          <bbn-numeric bbn-model="source.maxlength"
                       :min="1"
                       :max="1000"
                       :required="true"/>
        </div>

        <label bbn-if="types.decimal.includes(source.type)"><?= _("Precision") ?></label>
        <div bbn-if="types.decimal.includes(source.type)">
          <bbn-numeric bbn-model="source.decimals"
                       :min="1"
                       :max="20"
                       :required="true"/>
        </div>

        <label bbn-if="isNumber" ><?= _("Unsigned") ?></label>
        <div bbn-if="isNumber">
          <bbn-checkbox :value="0"
                        :novalue="1"
                        bbn-model="source.signed"/>
        </div>

        <label><?= _("Nullable") ?></label>
        <div>
          <bbn-checkbox bbn-model="source.null"
                        :value="true"
                        :novalue="false"/>
        </div>

        <label><?= _("Default Value") ?></label>
        <bbn-radio :source="defaultValueTypes"
                   bbn-model="defaultValueType" />

        <div bbn-if="['defined', 'expression'].includes(defaultValueType)">
        </div>
        <div bbn-if="defaultValueType === 'defined'">
          <component :is="defaultComponent"
                     bbn-bind="defaultComponentOptions"
                     bbn-model="source.default"
                     :required="true"/>
        </div>
        <div bbn-else-if="defaultValueType === 'expression'">
          <bbn-input bbn-model="source.default"
                     :required="true"/>
        </div>

        <label><?= _("Indexes") ?></label>
        <div>
          <bbn-radio :source="indexes"
                     bbn-model="source.index"/>
        </div>

      </template>
    </div>
    <div class="bbn-c bbn-w-100 bbn-vpadding">
      <bbn-button :label="buttonTitle"
                  :action="change"
                  :disabled="!isFormValid"
                  class="bbn-right-margin"/>
      <bbn-button :label="_('Cancel')"
                  :action="cancel"/>
    </div>
  </bbn-form>
</div>
