// Javascript Document
/*
* Max char length : 255
* Max varchar length : 65535
*/

(()=>{
  const defaults = {
    maxlength: null,
    decimals: null,
    type: '',
    defaultExpression: 0,
    default: '',
    extra: '',
    signed: 1,
    "null": 0,
    ref_table: '',
    ref_column: '',
    index: '',
    delete:'CASCADE',
    update:'CASCADE',
    charset: '',
    collation: ''
  };

  return {
    props: {
      source: {
        type: Object,
        required: true
      },
      db: {
        type: String
      },
      host: {
        type: String
      },
      engine: {
        type: String
      },
      otypes: {
        type: Array,
        default() {
          return [];
        }
      },
      predefined: {
        type: Array,
        default() {
          return [];
        }
      },
      constraints: {
        type: Array,
        default() {
          return [];
        }
      },
      charsets: {
        type: Array,
        default() {
          return [];
        }
      },
      collations: {
        type: Array,
        default() {
          return [];
        }
      },
      columns: {
        type: Array,
        default(){
          return [];
        }
      },
      windowed: {
        type: Boolean,
        default: false
      },
      options: {
        type: Boolean,
        default: false
      }
    },
    data(){
      const isNew = this.source.name === '';
      return {
        root: appui.plugins['appui-database'] + '/',
        isNew,
        oldName: this.source.name,
        question: isNew ? bbn._('What kind of column do you want to create ?') : bbn._('Edit your column here:'),
        checked: 0,
        defaultValueType: '',
        formData: {
          engine: this.engine,
          host: this.host,
          db: this.db,
          tables: this.tables
        },
        values: [],
        radioType: this.source.ref_column ? 'constraint' : (this.source.name || !this.predefined?.length ? 'free' : 'predefined'),
        predefinedType: "",
        columnsNamesOk: false,
        onDelete: [
          {
            text: bbn._("CASCADE"),
            value: 'CASCADE',
          },
          {
            text: bbn._("SET NULL"),
            value: 'SET NULL',
          },
          {
            text: bbn._("NO ACTION"),
            value: 'NO ACTION',
          },
          {
            text: bbn._("RESTRICT"),
            value: 'RESTRICT',
          },
        ],
        onUpdate: [
          {
            text: bbn._("CASCADE"),
            value: 'CASCADE',
          },
          {
            text: bbn._("SET NULL"),
            value: 'SET NULL',
          },
          {
            text: bbn._("NO ACTION"),
            value: 'NO ACTION',
          },
          {
            text: bbn._("RESTRICT"),
            value: 'RESTRICT',
          },
        ],
        indexes: [
          {
            text: bbn._("None"),
            value: '',
          },
          {
            text: bbn._("Primary"),
            value: 'primary',
          },
          {
            text: bbn._("Unique"),
            value: 'unique'
          },
          {
            text: bbn._("Index"),
            value: 'index'
          },
          {
            text: bbn._("FullText"),
            value: 'fulltext'
          }
        ],
        constraintIndexes: [
          {
            text: bbn._("Primary"),
            value: 'primary',
          },
          {
            text: bbn._("Unique"),
            value: 'unique'
          },
          {
            text: bbn._("Index"),
            value: 'index'
          }
        ],
        types: {
          int: [
            'int',
            'tinyint',
            'smallint',
            'mediumint',
            'bigint',
          ],
          decimal: [
            'float',
            'double',
            'decimal'
          ],
          values: [
            'enum',
            'set'
          ],
          text: [
            'tinytext',
            'text',
            'mediumtext',
            'longtext'
          ],
          date: [
            'date',
            'datetime',
            'year'
          ],
          time:[
            'time',
            'timestamp'
          ],
          char: [
            'char',
            'varchar',
            'binary',
            'varbinary'
          ],
          json: [
            'json'
          ],
        }
      };
    },
    computed: {
      radioTypes(){
        let res = [];
        if (this.predefined?.length) {
          res.push({
            text: bbn._('A predefined one'),
            value: 'predefined'
          });
        }

        if (this.constraints?.length) {
          res.push({
            text: bbn._('A reference to another table'),
            value: 'constraint'
          });
        }

        res.push({
          text: bbn._('Configure it yourself'),
          value: 'free'
        });
        return res;
      },
      constraint: {
        get() {
          return this.source.ref_table && this.source.ref_column ? this.source.ref_table + '.' + this.source.ref_column : '';
        },
        set(v) {
          if (v.indexOf('.') > 0) {
            let bits = v.split('.');
            this.$set(this.source, 'ref_table', bits[0]);
            this.$set(this.source, 'ref_column', bits[1]);
          }
          else {
            this.$set(this.source, 'ref_table', defaults.ref_table);
            this.$set(this.source, 'ref_column', defaults.ref_column);
          }
        }
      },
      colTypes () {
        return this.otypes.sort();
      },
      isNumber () {
        return (this.types.int.includes(this.source.type) || this.types.decimal.includes(this.source.type));
      },
      isChar () {
        return this.types.char.includes(this.source.type);
      },
      isValue () {
        return this.types.values.includes(this.source.type);
      },
      predefinedOptions() {
        return this.predefined.map(a => {
          return {
            text: a.text,
            value: a.id
          };
        });
      },
      defaultComponent () {
        if (this.defaultValueType === "defined") {
          if (this.types.int.includes(this.source.type)) {
            return "bbn-numeric";
          }
          else if (this.types.decimal.includes(this.source.type)) {
            return "bbn-numeric";
          }
          else if (this.types.text.includes(this.source.type)) {
            return "bbn-textarea";
          }
          else if (this.types.json.includes(this.source.type)) {
            return "bbn-json-editor";
          }
          else if (this.types.date.includes(this.source.type)) {
            return "bbn-datepicker";
          }
          else if (this.types.time.includes(this.source.type)) {
            return "bbn-timepicker";
          }
          else if (this.types.char.includes(this.source.type)) {
            return "bbn-input";
          }
          else if (this.types.values.includes(this.source.type)) {
            return "bbn-dropdown";
          }
        }
      },
      defaultComponentOptions () {
        if (this.defaultValueType === "defined") {
          if (this.types.int.includes(this.source.type)) {
            if (this.source.maxlength) {
              return {
                max: Math.pow(10, this.source.maxlength),
                min: this.source.signed ? 0 : - Math.pow(10, this.source.maxlength)
              };
            }
          }
          else if (this.types.decimal.includes(this.source.type)) {
            return {
              decimals: this.source.decimals
            };
          }
          else if (this.types.char.includes(this.source.type)) {
            return {
              size: this.source.maxlength
            };
          }
          else if (this.types.date.includes(this.source.type)) {
            if (this.source.type === "year") {
              return {
                type: "years"
              };
            }
          }
          else if (this.types.values.includes(this.source.type)) {
            return {
              source: this.values,
            };
          }
        }
        return {};
      },
      defaultValueTypes() {
        let res = [{
          text: bbn._("Null"),
          value: "null",
          disabled: !this.source.null
        }, {
          text: bbn._("SQL Expression"),
          value: 'expression'
        }, {
          text: bbn._("No default value"),
          value: ''
        }];

        bbn.fn.iterate(this.types, arr => {
          if (arr.includes(this.source.type)) {
            res.unshift({
              text: bbn._("As defined"),
              value: "defined"
            });
            return false;
          }
        });
        return res;
      },
      nameIsEmpty() {
        return this.source.name === '';
      },
      formButtons(){
        return [{
          label: bbn._('Cancel'),
          action: this.cancel,
          icon: 'nf nf-fa-times_circle'
        }, {
          label: bbn._('Save column'),
          disabled: !this.isFormValid,
          preset: 'submit'
        }];
      },
      isFormValid() {
        if (!this.source.name || !this.radioType || !this.columnsNamesOk) {
          return false;
        }

        switch (this.radioType) {
          case "constraint":
            if (!this.constraint) {
              return false;
            }

            break;
          case "predefined":
            if (!this.predefinedType) {
              return false;
            }

            break;
          case "free":
            if (!this.source.type) {
              return false;
            }

            if ((this.types.decimal.includes(this.source.type)
                || this.types.int.includes(this.source.type))
              && !this.source.maxlength
            ) {
              return false;
            }
            if (this.types.decimal.includes(this.source.type)
              && !this.source.decimals
            ) {
              return false;
            }

            break;
          default:
            return false;
        }

        return true;
      }
    },
    methods: {
      onSuccess(d, ev) {
        this.$emit("change", d, ev);
      },
      cancel() {
        let o = this.getRef('form').originalData;
        this.$emit("cancel", o.name ? o : null);
      },
      resetAll() {
        bbn.fn.iterate(defaults, (a, n) => {
          if (!['name', 'type', 'charset', 'collation'].includes(n)) {
            this.source[n] = a;
          }
        });
      },
      checkColumnsNames() {
        if (this.source?.name) {
          const form = this.getRef('form');
          this.columnsNamesOk = (form.originalData.name === this.source.name)
            || !bbn.fn.count(this.columns, c => (c !== this.source) && (c.name === this.source.name));
        }
      }
    },
    created(){
      if (this.options && (this.source.options === undefined)) {
        this.source.options = true;
      }
    },
    watch: {
      'source.name'(){
        this.checkColumnsNames();
      },
      'source.null'(v){
        if (!v && (this.defaultValueType === 'null')) {
          this.defaultValueType = '';
        }
      },
      constraint(v) {
        if (v) {
          let row = bbn.fn.getRow(this.constraints, {value: v});
          bbn.fn.log("COMPUTED CONSTRAINT", v, row, this.constraints);
          for (let n in row) {
            if (['ref_table', 'ref_column'].includes(n)) {
              continue;
            }

            if (this.source[n] !== undefined) {
              this.source[n] = row[n];
            }
            else {
              this.$set(this.source, n, row[n]);
            }
          }
          if (!this.source.index) {
            this.source.index = 'index';
          }
        }
      },
      defaultComponent(v) {
        switch (v) {
          case "bbn-numeric":
            this.source.default = 0;
            break;
          case "bbn-textarea":
          case "bbn-input":
          case "bbn-json-editor":
            this.source.default = "";
            break;
          case "bbn-datepicker":
          case "bbn-timepicker":
            this.source.default = bbn.fn.dateSQL();
            break;
        }
      },
      defaultValueType(v) {
        if (v === "null") {
          this.$set(this.source , 'default', null);
        }
        else if (v === '') {
          delete this.source.default;
        }
        else {
          if (this.types.decimal.includes(this.source.type) || this.types.int.includes(this.source.type)) {
            this.$set(this.source, 'default', 0);
          }
          else {
            this.$set(this.source, 'default', "");
          }
        }
        if (v === "expression") {
          this.source.defaultExpression = 1;
        }
        else if (this.source.defaultExpression) {
          this.source.defaultExpression = 0;
        }
      },
      predefinedType(v) {
        if (v) {
          let o = bbn.fn.getRow(this.predefined, {id: v});
          bbn.fn.each(Object.keys(defaults), n => {
            this.source[n] = o[n] === undefined ? defaults[n] : o[n];
          });
          this.radioType = this.constraint ? 'constraint' : 'free';
          this.predefinedType = "";
          this.$forceUpdate();
        }
      },
      radioType(v, ov) {
        if (ov === 'predefined') {
          return;
        }

        bbn.fn.log(`RADIO TYPE IS CHANGED FROM ${ov} to ${v}`);
        this.resetAll();
      },
    }
  };
})();
