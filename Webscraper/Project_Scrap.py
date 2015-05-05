import sys

class Project_Scrap(object):
	def __init__(self):
		self.name = ''
		self.current_funding = -1
		self.target_funding = -1
		self.catagories = ''
		self.min_invest = -1
		self.end_date = ''
		self.backer_count = -1
		self.company_logo = '' #html link
		self.source = '' #html link
		self.source_site = '' 
	def unassigned(self):
		flag = True
		if self.name == '':
			print >> sys.stderr, 'Warning: <name> variable not scanned.'
			flag = False
		if self.current_funding == -1:
			print >> sys.stderr, 'Warning: <current_funding> variable not scanned.'
			flag = False
		if self.target_funding == -1:
			print >> sys.stderr, 'Warning: <target_funding> variable not scanned.'
			flag = False
		if self.catagories == '':
			print >> sys.stderr, 'Warning: <catagories> variable not scanned.'
			flag = False
		if self.min_invest == -1:
			print >> sys.stderr,  'Warning: <mid_invest> variable not scanned.'
			flag = False
		if self.end_date == '':
			print >> sys.stderr,  'Warning: <end_date> variable not scanned.'
			flag = False
		if self.backer_count == -1:
			print >> sys.stderr,  'Warning: <backer_count> variable not scanned.'
			flag = False
		if self.company_logo == '':
			print >> sys.stderr, 'Warning: <company_logo> variable not scanned.'
			flag = False
		if self.source == '':
			print >> sys.stderr, 'Warning: <source> variable not scanned.'
			flag = False
		if self.source_site == '' :
			print >> sys.stderr, 'Warning: <source_site> variable not scanned.'
			flag = False
		
		if flag:
			return False
		else:
			return True
	def insert_Command(self):
		if not isinstance(self.target_funding, ( int, long )):
			self.target_funding = int(self.target_funding.replace(",", ""))
		if not isinstance(self.current_funding, ( int, long )):
			self.current_funding = int(self.current_funding.replace(",", ""))
		if not isinstance(self.min_invest, ( int, long )):
			self.min_invest = int(self.min_invest.replace(",", ""))
		if not isinstance(self.backer_count, ( int, long )):
			self.backer_count = int(self.backer_count.replace(",", ""))
		return """INSERT INTO Project(name, current_funding, target_funding, 
		categories, min_invest, end_date, backer_count, company_logo, source, source_site) 
		VALUES ('{}', {}, {}, '{}', {}, '{}', {}, '{}', '{}', '{}')""".format(self.name,
				self.current_funding,
				self.target_funding, self.catagories, self.min_invest,
                self.end_date, self.backer_count, self.company_logo,
                self.source, self.source_site)
