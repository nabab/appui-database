// Javascript Document
/*
* Max char length : 255
* Max varchar length : 65535
*/

(()=>{
  return {
    props: ['source'],
    data(){
      let data = {
        engine: this.source.engine,
        host: this.source.host,
        db: this.source.name
      };
      return {
        root: appui.plugins['appui-database'],
        checked: 0,
        defaultValue: '',
        formData: data,
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
        },
        formSource: {
          name: "",
          type: "",
          max_length: 0,
          unsigned: "",
          decimal: 0,
          nullable: "",
          default_value: "",
          index: "",
        }
      };
    },
    computed: {
      colTypes () {
        return this.source.types.sort();
      },
      isNumber () {
        return (this.types.int.includes(this.formSource.type) || this.types.decimal.includes(this.formSource.type));
      },
      isChar () {
        return this.types.char.includes(this.formSource.type);
      },
      defaultComponent () {
        if (this.defaultValue === "defined") {
          if (this.types.int.includes(this.formSource.type)) {
            return "bbn-numeric";
          }
          else if (this.types.decimal.includes(this.formSource.type)) {
            return "bbn-numeric";
          }
          else if (this.types.text.includes(this.formSource.type)) {
            return "bbn-textarea";
          }
          else if (this.types.json.includes(this.formSource.type)) {
            return "bbn-json-editor";
          }
          else if (this.types.date.includes(this.formSource.type)) {
            return "bbn-datepicker";
          }
          else if (this.types.time.includes(this.formSource.type)) {
            return "bbn-timepicker";
          }
          else if (this.types.char.includes(this.formSource.type)) {
            return "bbn-input";
          }
          else if (this.types.values.includes(this.formSource.type)) {
            return "bbn-values";
          }
        }
      },
      defaultComponentOptions () {
        if (this.defaultValue === "defined") {
          if (this.types.int.includes(this.formSource.type)) {
            if (this.formSource.max_length) {
              return {
                max: Math.pow(10, this.formSource.max_length),
                min: this.formSource.unsigned ? 0 : - Math.pow(10, this.formSource.max_length)
              };
            }
          }
          else if (this.types.decimal.includes(this.formSource.type)) {
            return {
              decimals: this.formSource.decimal
            };
          }
          else if (this.types.char.includes(this.formSource.type)) {
            return {
              size: this.formSource.max_length
            };
          }
          else if (this.types.date.includes(this.formSource.type)) {
            if (this.formSource.type === "year") {
              return {
                type: "years"
              };
            }
          }
        }
        return {};
      },
      defaultValues() {
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
        if (this.formSource.nullable) {
          res.unshift({text: bbn._("Null"), value: null});
        }
        bbn.fn.iterate(this.types, arr=>{
          if (arr.includes(this.formSource.type)) {
            res.unshift({text: bbn._("As defined"), value: "defined"});
            return false;
          }
        });
        return res;
      },
      nameIsEmpty() {
        return this.formSource.name === '';
      },
      isNull: {
        get() {
          return this.formSource.nullable && (this.formSource.default_value === null);
        },
        set(v) {
          if (v) {
            if (!this.formSource.nullable) {
              throw new Error("The checkbox is null must be checked");
            }
            this.formSource.default_value = null;
          }
          else {
            this.formSource.default_value = "";
          }
        }
      },
    },
    methods: {
      success(){},
    },
    watch: {
      defaultComponent(v, u) {
        switch (v) {
          case "bbn-numeric":
            this.formSource.default_value = 0;
            break;
          case "bbn-textarea":
          case "bbn-input":
          case "bbn-json-editor":
            this.formSource.default_value = "";
            break;
          case "bbn-datepicker":
          case "bbn-timepicker":
            this.formSource.default_value = bbn.fn.dateSQL();
            break;
        }
      }
    },
  };
})();