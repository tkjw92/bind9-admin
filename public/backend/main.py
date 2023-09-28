import mysql.connector
import random
import os

os.system('rm -f /etc/bind/db.*')

template_named = '''
include "/etc/bind/named.conf.options";
include "/etc/bind/named.conf.local";
'''

named_file = open('/etc/bind/named.conf', 'w')
named_file.write(template_named)

MysqlDB = mysql.connector.connect(
    host='localhost',
    username='admin',
    password='123',
    db='bind9'
)

DB = MysqlDB.cursor()

zone_file = open('/etc/bind/named.conf.local', 'w')

DB.execute('SELECT * FROM tb_zones')
zones = DB.fetchall()

tmp_zone = ''

template_master = '\nzone "zone_name" {\n\ttype master;\n\tfile "/etc/bind/db.zone_name";\n};\n'
template_masters = '\nzone "zone_name" {\n\ttype master;\n\tfile "/etc/bind/db.zone_name";\n\tallow-transfer {peers};\n};\n'
template_slave = '\nzone "zone_name" {\n\ttype slave;\n\tfile "/var/cache/bind/db.zone_name";\n\tmasters {peers};\n};\n'

template_db = '''
$TTL    604800
@       IN      SOA     zone_name. root.zone_name. (
                           rand         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
;
@       IN      NS      zone_name.
'''

for i in zones:
    if i[1] == 'master':
        if i[3] == None:
            x = template_master.replace('zone_name', i[2])
            tmp_zone += x
        else:
            x = template_masters.replace('zone_name', i[2]).replace('peers', i[3])
            tmp_zone += x
        
        
        db_forward = open(f'/etc/bind/db.{i[2]}', 'w')
        y = template_db.replace('zone_name', i[2]).replace('rand', str(random.randint(1000,9999)))
        
        DB.execute(f"SELECT * FROM tb_records WHERE zone = '{i[2]}'")
        records = DB.fetchall()
        
        for j in records:
            if j[2] == 'cname' or j[2] == 'mx':
                z = j[4] + '.'
            else:
                z = j[4]
                
            y += f'\n{j[3]}\tIN\t{j[2]}\t{z}'
        db_forward.write(y)
        
    else:
        x = template_slave.replace('zone_name', i[2]).replace('peers', i[3])
        tmp_zone += x

DB.execute('SELECT * FROM tb_ptr')
ptrs = DB.fetchall()

for i in ptrs:
    if i[1] == 'master':
        if i[4] == None:
            x = template_master.replace('zone_name', i[3])
            tmp_zone += x
        else:
            x = template_masters.replace('zone_name', i[3]).replace('peers', i[4])
            tmp_zone += x
            
        db_reverse = open(f'/etc/bind/db.{i[3]}', 'w')
        y = template_db.replace('zone_name', i[2]).replace('rand', str(random.randint(1000,9999)))
        
        DB.execute(f"SELECT * FROM tb_records_ptr WHERE ptr = '{i[3]}'")
        records = DB.fetchall()
        
        for j in records:
            z = j[3] + '.'
                
            y += f'\n{j[2]}\tIN\tPTR\t{z}'
        db_reverse.write(y)
        
    else:
        x = template_slave.replace('zone_name', i[3]).replace('peers', i[4])
        tmp_zone += x
    

template_conf = '''
acl allowed {
    @allowed
};

options {
        directory "/var/cache/bind";

        allow-query { allowed; };
        allow-transfer { "none"; };

        @forwarders

        recursion @recursion;

        dnssec-validation no;

        listen-on-v6 { any; };
};
'''

DB.execute('SELECT * FROM tb_config')
conf = DB.fetchall()
conf = conf[0]

template_conf = template_conf.replace('@allowed', conf[1])
template_conf = template_conf.replace('@recursion', conf[2])

f1 = '' if conf[3] == None else conf[3]
f2 = '' if conf[4] == None else conf[4]
f3 = '' if conf[5] == None else conf[5]
f4 = '' if conf[6] == None else conf[6]

if f1 == '' and f2 == '' and f3 == '' and f4 == '':
    template_conf = template_conf.replace('@forwarders', '')
else:
    forwarder = ''
    if f1 != '':
        forwarder += f1 + ';'
    if f2 != '':
        forwarder += f2 + ';'
    if f3 != '':
        forwarder += f3 + ';'
    if f4 != '':
        forwarder += f4 + ';'

    template_conf = template_conf.replace('@forwarders', 'forwarders {@x};'.replace('@x', forwarder))

option_file = open('/etc/bind/named.conf.options', 'w')
option_file.write(template_conf)

zone_file.write(tmp_zone)
os.system('service bind9 restart')
