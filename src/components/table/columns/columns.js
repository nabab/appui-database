  ( () => {
    return {
      data() {
        let columns = [
          {
            title: ' ',
            cls: 'bbn-c',
            width: 40,
            buttons: this.getButtons,
          }, {
            field: 'position',
            title: '<a title=\'' + bbn._('Position in the table') + '>#</a>',
            cls: 'bbn-c',
            width: '40'
          }, {
            field: 'key',
            title: '<i class=\'nf nf-fa-key\' title=\'' + bbn._('Are there keys on the column?') + '></i>',
            render: this.writeKeyInCol,
            cls: 'bbn-c bbn-bg-black bbn-xl',
            width: '40'
          }, {
            field: 'name',
            render: this.writeColumn,
            title: bbn._('Columns'),
          }, {
            field: 'type',
            title: '' + bbn._('Type'),
            cls: 'bbn-c',
            render: this.writeType,
            width: '100'
          }, {
            field: 'maxlength',
            title: '' + bbn._('Length'),
            cls: 'bbn-c',
            width: '70'
          }, {
            field: 'null',
            title: '<i class=\'nf nf-fa-ban\' title=\'' + bbn._('Can the field be null?') + '></i>',
            cls: 'bbn-c',
            width: '40',
            render: this.writeNull
          }, {
            field: 'default_value',
            title: '' + bbn._('Default'),
            render: this.writeDefault,
            cls: 'bbn-c',
            width: '80'
          }];
        return {
          columns: columns,
          cp: null,
        };
      },
      props: ['source'],
      computed: {
        tableSource(){
          let r = [];
          bbn.fn.iterate(this.source.structure.fields, (a, n) => {
            r.push(bbn.fn.extend({name: n}, a));
          });
          return r;
        },
        editorOptions() {
          let editColumnsData = this.source.editColumnsData;
          return this.cp ? {
              db: this.cp.source.db,
              host: this.cp.source.host,
              engine: this.cp.source.engine,
              table: this.cp.source.table,
              otypes:  editColumnsData.mysql.types,
              predefined: editColumnsData.mysql.predefined,
              root: editColumnsData.mysql.root,
            } : {};
        }
      },
      methods: {
        editOption(row) {
          bbn.fn.log("Hello", arguments);
          this.getPopup({
            width: '30em',
            height: '15em',
            source: row,
            title: false,
            component: 'appui-database-table-columns-option'
          });
        },
        getButtons(row) {
          let button = [
            {
              text: bbn._('Edit column'),
              action: 'edit',/*() => {
                this.update(row);
              },*/
              icon: 'nf nf-fa-edit'
            },
            {
              text: bbn._('Remove'),
              action: () => {
                this.remove(row);
              },
              icon: 'nf nf-fa-times'
            },
            {
              text: bbn._('Move Up'),
              action: () => {
                this.moveUp(row);
              },
              icon: 'nf nf-fa-arrow_up'
            },
            {
              text: bbn._('Move Down'),
              action: () => {
                this.moveDown(row);
              },
              icon: 'nf nf-fa-arrow_down'
            }
          ];
          if (row.option) {
            button.push(
              {
                text: bbn._('Edit Option'),
                action: () => {
                  this.editOption(row);
                },
                icon: 'nf nf-fa-edit'
              }
            );
          }
          return button;
        },
        getStateColor(row) {
          let col = false;
          if (!row.option) {
            col = 'red';
          }
          return col;
        },
        writeKeyInCol(row) {
          if (!row.key) {
            return ' ';
          }
          return '<i class="nf nf-fa-key ' + (row.key === 'PRI' ? 'bbn-yellow' : 'bbn-grey') + '"> </i>';
        },
        writeType(row) {
          if (row.type === 'int') {
            row.type += ' (<em>' + (row.signed ? '' : 'un') + 'signed)</em>';
          }
          return row.type;
        },
        writeColumn(row) {
          let col = this.getStateColor(row);
          let st = '';
          if (row.option && (row.option.text != row.name)) {
            st += row.option.text + " (" + row.name + ")";
          }
          else {
            st += '<span' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</span>';
          }
          if (this.source.constraints[row.name]) {
            st += ' (' + bbn._('refers to') + ' ' + this.source.constraints[row.name].column + ' ' + bbn._('in') + ' ' + this.source.constraints[row.name].table + ')';
          }
          return st;
        },
        writeNull(row) {
          return row.null ? '<i class="nf nf-fa-check"> </i>' : ' ';
        },
        writeDefault(row) {
          return row.default || '-';
        },
        remove() {
          return;
        },
        update(data, col, idx) {
          let cp = this.closest('bbn-container');
          data.olddecimal = data.decimal;
          data.olddefault = data.default;
          data.olddefaultExpression = data.defaultExpression;
          data.oldname = data.name;
          data.oldtype = data.type;
          data.oldsigned = data.signed;
          data.oldnull = data.null;
          data.oldkey = data.key;
          data.oldmaxlength = data.maxlength;
          data.oldindex = data.index;
          let editColumnsData = this.source.editColumnsData;
          this.getPopup({
            title: 'Edit a column',
            component: 'appui-database-column-editor',
            source : {
              db: cp.source.db,
              host: cp.source.host,
              engine: cp.source.engine,
              table: cp.source.table,
              otypes:  editColumnsData.mysql.types,
              predefined: editColumnsData.mysql.predefined,
              source: data,
              root: editColumnsData.mysql.root,
            },
          });
          return;
        },
        moveUp(idx) {
          if (idx.position > 1) {
            let tmp = idx.position - 1;
            bbn.fn.move(this.tableSource, tmp, tmp - 1);
            bbn.fn.log('idx + tableSource', idx, this.tableSource);
          }
          return;
        },
        moveDown (idx) {
          if (idx.position < this.tableSource.length) {
            let tmp = idx.position - 1;
            bbn.fn.move(this.tableSource, tmp, tmp + 1);
            //bbn.fn.moveColumn(this.tableSource, tmp, tmp + 1);
          }
          return;
        },
        changeColPosition() {
          return;
        },
        makePredefined() {
          return;
        },
        addKey() {
          return;
        },
      },
      mounted() {
        this.cp = this.closest('bbn-container');
      }
    };
  })();