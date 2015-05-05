from Project_Scrap import Project_Scrap
from selenium import webdriver
from Investor_Scrap import Investor_Scrap
import json
import sys
import os
import urllib
import MySQLdb
from pprint import pprint
from time import sleep
import re

test = False
plugin_dir = './Plugins'
plugins = []


#Read in the /plugins directory and store the plugins for use.
files = os.listdir(plugin_dir)

for f in files:
        if os.path.isdir(f):
                continue
        
        with open('Plugins/'+f) as plug:
                plugins.append(json.loads(plug.read()))
        

def detail_page(url,plugin):
        """This method is given a url, which should be of an indiviual project or
        investor, and the plugin associated with them. It generates a scrap object
        from scraped data on the page, and returns it. 
        """

        #Download the HTML for the page in question.
        urllib.urlretrieve (url, "html.html")
        ret = ''
        #make the data storage class
        if plugin['type'] == 'Project':
                ret = Project_Scrap()
        else:
                ret = Investor_Scrap()
        
        #Scraper itself
        for p in plugin['details']:
                f = open("html.html", 'r')
                failureTicker = 0
                
                #individual read in type. 
                if p['type'] == 'line':
                        output = ''
                        #skip to the the line specified if it exists.
                        if p['skip'] != '':
                                x = 'start'
                                while x != p['skip']+'\n':
                                        if x == '':
                                                failureTicker+=1
                                                if failureTicker > 50:
                                                        break
                                        x = f.readline()
                        #readlines until the tag starting the information we need is read.
                                x = 'start'
                                while x != p['start']+'\n':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break
                                        x = f.readline()
                        
                        #add any lines in between the start line just read and the close
                        #tag to the output string.
                        while 1:
                                x = f.readline()
                                if x == p['end']+'\n':
                                        break
                                output = output + x
                                if x == '':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break
                                
                        
                        #removes all dollar signs from the output.
                        output = output.replace("$", "")
                        output = output.replace("\n", "")
                        
                        #Pulls links out of a long tag.
                        if 'href' in output:
                                out = output.split(' ')
                                for o in out:
                                        if o[0:4] == 'href':
                                                output = o[6:-1]
                        if 'src' in output:
                                out = output.split(' ')
                                for o in out:
                                        if o[0:3] == 'src':
                                                output = o[7:-1]        
                        if output.isdigit():
                                output = int(output)
                        
                        cat = p['name']
                        #sets the scrap variable named specified in the plugin to the output.
                        exec('ret.' + cat + ' = output') in locals()


                #block read in type. Crowdrabbit only
                elif p['type'] == 'block':
                        x = 'start'
                        while x != p['start']+'\n':
                                if x == '':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break
                                x = f.readline()
                                        
                        #read every line between a start and end tag. each line is checked for a start or
                        #end tag, depending on the last one seen. in between them is stored in an array,
                        #outside is not. This is done until the block end tag is found.
                        scanned = []
                        flag = False
                        output = ''
                        while 1:
                                x = f.readline()
                                if x == p['end']+'\n':
                                        break
                                elif x[:-1] in p['start_tags'] and flag == False:
                                        flag = True
                                        output = ''
                                elif x[:-1] in p['end_tags'] and flag == True:
                                        flag = False
                                        scanned.append(output)
                                elif flag == True:
                                        x = x.replace("$", "")
                                        x = x.replace("\n", "")
                                        output= output + x
                                elif flag == False:
                                        pass
                                else:
                                        print 'Something dun fucked up'

                                if x == '':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break
                        #Look at the scanned data, and store it appropriately. 
                        for x in range(1, len(scanned)):
                                if scanned[x] in p['translations']:
                                        var = p['translations'][scanned[x]]
                                        exec('ret.' + var + ' = ' + repr(scanned[x-1])) in locals()

                #Regular expression reading of a single line.
                elif p['type'] == 'single_line':
                        reg = re.compile(p['start'])
                        while 1:
                                x = f.readline()
                                if len(reg.findall(x)) > 0:
                                        reg = re.compile(p['regex'])
                                        listx = reg.findall(x)
                                        if test : print p["index"], len(listx), listx
                                        cat = p['name']
                                        duck = listx[p['index']][p['cut_start']:p['cut_end']]
                                        #sets the scrap variable named specified in the plugin to the output.
                                        exec('ret.' + cat + ' = ' + repr(duck)) in locals()
                                        break
                                if x == '':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break

                #Regular expression section scanner.
                elif p['type'] == 'single_block':
                        reg = re.compile(p['start'])
                        while 1:
                                x = f.readline()
                                if len(reg.findall(x)) > 0:
                                        reg = re.compile(p['end'])
                                        regmatch = re.compile(p['keys'])
                                        while 1:
                                                x = f.readline()
                                                if len(reg.findall(x)) > 0:
                                                        break
                                                if len(regmatch.findall(x)) > 0:
                                                        regger = re.compile(p['regex'])
                                                        listx = regger.findall(x)
                                                        cat = p['name']
                                                        if test : print p["index"], len(listx), listx
                                                        if p['cut_start'] != 0 and p['cut_end'] != 0:
                                                                duck = listx[p['index']][p['cut_start']:p['cut_end']] + ', '
                                                        else:
                                                                duck = listx[p['index']][:] + ' '
                                                        #sets the scrap variable named specified in the plugin to the output.
                                                        exec('ret.' + cat + " += " + repr(duck)) in locals()
                                                if x == '':
                                                        failureTicker+=1
                                                        if failureTicker > 50:
                                                                break
                                        break
                                if x == '':
                                        failureTicker+=1
                                        if failureTicker > 50:
                                                break
                else:
                        print "plugin has invalid readtype."
                f.close()
        return ret                      
                                        
def mega_list_page(plugin):
        """Iterates through a plugin's list page, or page with all the detail pages on it
        and gets the scraps from detail_page. It then sends those scraps to the specificed location.
        """
        loadButton = plugin['loader']
        driver = webdriver.Firefox()
        driver.get(plugin['list_page'])
        sleep(5)
        x = 0
        try:
                while x < 2:
                        driver.find_element_by_xpath(loadButton).click()
                        sleep(2)
                        x= x+1
        except:
                pass
        f = open('list.html','w')
        f.write(driver.page_source.encode('ascii','ignore'))
        f.close()
        driver.quit()
        f = open('list.html', 'r')
        flag = False
        for line in f:
                if flag:
                        flag = False
                        split = line.split(' ')
                        second = ''
                        for o in split:
                                if o[0:9] == 'ng-href="':
                                        second = o[9:-1]
                                        break
                                elif o[0:6] == 'href="':
                                        second = o[6:-1]
                                        break
                        cat = plugin['project_prepend']+''.join(second)+'.html'
                        if test: print cat
                        ret = detail_page(cat,plugin)
                        send_scrap(ret,plugin)
                if line == plugin['project_start']+'\n':
                        flag = True
        f.close()
        
def send_scrap(scrap, plugin):
        """Sends a scrap to a source in a plugin."""
        pprint (vars(scrap))
        db=MySQLdb.connect(host=plugin['address'], user=plugin['user'], passwd=plugin['pass'], db=plugin['db'])
        c=db.cursor()
        if test: print scrap.insert_Command()

        exists = c.execute("SELECT * FROM {0} WHERE EXISTS (SELECT name FROM {0} WHERE name = '{1}');".format(plugin['type'], scrap.name))
        if exists:
                c.execute("DELETE FROM {0} WHERE name = '{1}'".format(plugin['type'], scrap.name))
                c.execute(scrap.insert_Command())
        else:
                c.execute(scrap.insert_Command())
        db.commit()

#Runs the code.
if __name__ == '__main__':
        for p in plugins:  
                if p['list_type'] == 'mega':
                        mega_list_page(p)
