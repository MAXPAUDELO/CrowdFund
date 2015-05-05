import sys

class Investor_Scrap(object):
	def __init__(self):
		self.name = ''
		self.location = ''
		self.investments = ''
		self.average_invested = -1
		self.profession = ''
		self.target_market = ''
		self.target_location = ''
		self.company_title = '' 
		self.source_url = '' #html link
		self.source_site = '' 
	def unassigned(self):
		flag = True
		if self.name == '':
			print >> sys.stderr, 'Warning: <name> variable not scanned.'
			flag = False
		if self.location == '':
			print >> sys.stderr, 'Warning: <location> variable not scanned.'
			flag = False
		if self.investments == '':
			print >> sys.stderr, 'Warning: <investments> variable not scanned.'
			flag = False
		if self.average_invested == -1:
			print >> sys.stderr, 'Warning: <average_invested> variable not scanned.'
			flag = False
		if self.profession == '':
			print >> sys.stderr,  'Warning: <mid_invest> variable not scanned.'
			flag = False
		if self.target_market == '':
			print >> sys.stderr,  'Warning: <target_market> variable not scanned.'
			flag = False
		if self.target_location == '':
			print >> sys.stderr,  'Warning: <target_location> variable not scanned.'
			flag = False
		if self.company_title == '':
			print >> sys.stderr, 'Warning: <company_title> variable not scanned.'
			flag = False
		if self.source_url == '':
			print >> sys.stderr, 'Warning: <source_url> variable not scanned.'
			flag = False
		if self.source_site == '':
			print >> sys.stderr, 'Warning: <source_site> variable not scanned.'
			flag = False
		
		if flag:
			return False
		else:
			return True
	def insert_Command(self):
		return """INSERT INTO Investor(name, location, investments, average_invested, profession, 
		target_market, target_location, company_title, source_url, source_site)
        VALUES ('{0}', '{1}', '{2}', {3}, '{4}', '{5}', '{6}', '{7}', '{8}', '{9}')""".format(self.name,
		self.location, self.investments,
        repr(self.average_invested), self.profession, self.target_market,
        self.target_location, self.company_title, self.source_url, self.source_site)
	
