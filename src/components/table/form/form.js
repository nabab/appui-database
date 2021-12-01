// Javascript Document


(()=>{
  const defaultColumn = {
    name: "",
    type: "",
    maxlength: null,
    signed: 1,
    decimal: null,
    'null': "",
    index: "",
    defaultExpression: 0,
    extra: "",
    constraint: "",
  };
  return {
    props: ['source'],
    data () {
      let data = {
        name: this.name,
        comment: this.comment,
        columns: []
      };
      return {
        root: appui.plugins["appui-database"] + '/',
        name: '',
        comment: '',
        edited: -1,
        formData: data,
        keys: [],
        cols: []
      };
    },
    computed: {
      formButtons() {
        return ["cancel", {
          text: bbn._("Submit"),
          action: ()=> {
            let form = this.$refs.form;
            form.submit();
            bbn.fn.link(this.root+ 'tabs/' + this.source.engine + '/' + this.source.host + '/' + this.source.db + '/' + this.formData.name + '/columns');
          },
          disabled: false,
        }];
      },
      numMovableColumns() {
        let tmp = this.formData.columns.length;
        if (this.edited !== -1) {
          tmp--;
        }
        return tmp;
      },
      tableData() {
        let res = {
          db: this.source.db,
          host: this.source.host,
          engine: this.source.engine,
          keys: {},
          cols: {},
          fields: {}
        };
        const excluded = ['name', 'index', 'constraint'];
        bbn.fn.each(this.formData.columns, a => {
          res.fields[a.name] = {};
          for (let n in a) {
            if (!excluded.includes(n)) {
              res.fields[a.name][n] = a[n];
            }
            if ((n === 'constraint') && a[n]) {
              res.cols[a.name] = [a.name];
              res.fields[a.name].key = 'MUL';
              let tmp = a[n].split('.');
              res.keys[a.name] = {
                columns: [
                  a.name
                ],
                ref_db: this.source.db,
                ref_table: tmp[0],
                ref_column: tmp[1],
                update: "CASCADE",
                delete: "CASCADE",
                unique: 0
              };
            }
            else if ((n === 'index') && a[n]) {
              res.cols[a.name] = [a[n] === 'primary' ? 'PRIMARY' : a.name];
              res.fields[a.name].key = a[n] === 'primary' ? 'PRI' : 'MUL';
              res.keys[a[n] === 'primary' ? 'PRIMARY' : a.name] = {
                columns: [
                  a.name
                ],
                unique: ['primary', 'unique'].includes(a[n]) ? 1 : 0
              };
            }
          }
        });
        return res;
      },
    },
    methods: {
      addColumn(idx, cfg) {
        bbn.fn.log("Add column", idx, cfg);
        if (this.formData.columns[idx]) {
          this.formData.columns.splice(idx, 0, bbn.fn.extend({}, defaultColumn, cfg || {}));
        }
        else {
          this.formData.columns.push(bbn.fn.extend({}, defaultColumn, cfg || {}));
          idx = this.formData.columns.length - 1;
        }
        this.edited = idx;
      },
      onCancel() {
        this.formData.columns.splice(this.edited, 1);
        this.edited = -1;
      },
      getColDescription(col) {
        let str = col.type;
        if (col.maxlength) {
          str += " (" + col.maxlength + ")";
        }
        if (!col.null) {
          str += " NOT NULL";
        }
        if (this.defaultValueType) {
          str += " DEFAULT ";
          if (col.default === null) {
            str += "NULL";
          }
          else if (!col.defaultExpression && bbn.fn.isString(col.default)) {
            str += '"' + bbn.fn.replaceAll('"', '\\"', col.default) + '"';
          }
          else {
            str += col.default;
          }
        }
        return str;
      },
      moveUp(idx) {
        if (this.formData.columns[idx] && this.formData.columns[idx - 1]) {
          bbn.fn.move(this.formData.columns, idx, idx - 1);
        }
      },
      moveDown(idx) {
        if (this.formData.columns[idx] && this.formData.columns[idx + 1]) {
          bbn.fn.move(this.formData.columns, idx, idx + 1);
        }
      },
    },
    watch: {
      values (v) {
        if (!v.length) {
          this.source.extra = "";
        }
        else {
          this.source.extra = v.map(a => {
            return "'" + bbn.fn.replaceAll(a, "'", "\\'") + "'";
          }).join(",");
        }
      },
    }
  };
})();