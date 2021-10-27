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
      types: {}
    },
    data(){
      let data = {
        engine: this.engine,
        host: this.host,
        db: this.db
      };
      return {
        formIsValid: true,
        root: appui.plugins['appui-database'] + '/',
        checked: 0,
        defaultValueType: '',
        formData: data,
        values: [],
        radioType: 'free',
        constraintTable: "",
        constraintColumn: "",
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
            'varchar'
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
    },
    methods: {
      success(){},
      cancel() {
        this.$emit("cancel");
      },
      change() {
        this.$emit("change");
      },
			resetMaxLength() {
				this.source.maxlength = null;
      },
    },
    watch: {
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
          this.source.default = null;
        }
        else {
          this.source.default = "";
        }
        if (v === "expression") {
          this.source.defaultExpression = 1;
        }
        else if (this.source.defaultExpression) {
          this.source.defaultExpression = 0;
        }
      },
      source: {
        deep: true,
        handler() {
          let form = this.$refs.form;
          if (form) {
            this.formIsValid = form.isValid();
          }
        }
      },
    },
  };
})();