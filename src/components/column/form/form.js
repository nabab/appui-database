// Javascript Document
/*
* Max char length : 255
* Max varchar length : 65535
*/

(()=>{
  return {
    props: {
      source: {},
      db: {},
      host: {},
      engine: {},
      otypes: {},
      predefined: {},
      tables: {},
    },
    data(){
      let data = {
        engine: this.engine,
        host: this.host,
        db: this.db
      };
      return {
        formIsValid: false,
        root: appui.plugins['appui-database'] + '/',
        checked: 0,
        defaultValueType: '',
        formData: data,
        values: [],
        radioType: 'free',
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
            value: a.code
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
              decimals: this.source.decimal
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
        let res =[
          {
            text: bbn._("SQL Expression"),
            value: 'expression',
          },
          {
            text: bbn._("No default value"),
            value: ''
          },
        ];
        if (this.source.null) {
          res.unshift({text: bbn._("Null"), value: "null"});
        }
        bbn.fn.iterate(this.types, arr=>{
          if (arr.includes(this.source.type)) {
            res.unshift({text: bbn._("As defined"), value: "defined"});
            return false;
          }
        });
        return res;
      },
      nameIsEmpty() {
        return this.source.name === '';
      },
      isFormValid() {
        if (!this.source.name || !this.radioType || !this.columnsNamesOk) {
          return false;
        }
        switch (this.radioType) {
          case "constraint":
            if (!this.source.constraint) {
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
            if ((this.types.decimal.includes(this.source.type) || this.types.int.includes(this.source.type)) && !this.source.maxlength) {
              return false;
            }
            if (this.types.decimal.includes(this.source.type) && !this.source.decimal) {
              return false;
            }
            break;
          default:
            return false;
        }
        return true;
      },
    },
    methods: {
      success(){},
      cancel() {
        this.$emit("cancel");
      },
      change() {
        this.$emit("change");
      },
      resetAll() {
        this.source.maxlength = null;
        this.source.decimal = null;
        this.source.defaultExpression = 0;
        this.source.signed = 1;
        this.source.null = 0;
        this.source.constraint = "";
        this.source.delete="cascade";
        this.source.update="cascade"
      },
      checkColumnsNames() {
        let cp = this.closest("appui-database-table-form");
        let num = bbn.fn.count(cp.formData.columns, {name: this.source.name});
        this.columnsNamesOk = num <= 1;
      },
    },
    watch: {
      'source.constraint'(v) {
        if (v) {
          let row = bbn.fn.getRow(this.tables, {value: v});
          for (let n in row) {
            if (this.source[n] !== undefined) {
              this.source[n] = row[n];
            }
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
          let o = bbn.fn.getRow(this.predefined, {code: v});
          let ar = ['type', 'maxlength', 'signed', 'decimal', 'null', 'default', 'index', 'defaultExpression', 'extra'];

          bbn.fn.each(ar, a => {
            if (o[a] !== undefined) {
              this.$set(this.source, a, o[a]);
            }
          });
          this.radioType = 'free';
          this.predefinedType = "";
        }
      },
      radioType(v, ov) {
        if ((ov === 'predefined') && (v === 'free')) {
          return;
        }
        this.resetAll();
        this.source.type = "";
      },
    },
  };
})();