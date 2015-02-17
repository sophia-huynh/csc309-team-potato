from django.db import models
import datetime
from django.utils import timezone

# character lengths have been hardcoded for now and arbitratily chosen
# this could be something an admin could modify
    
class User(models.Model):
    uid = models.AutoField(primary_key=True)
    name = models.CharField(max_length = 16)
    email = models.EmailField(max_length = 254)
    
    def __str__(self):
        return "{}: {}".format(self.uid, self.name)

class Project(models.Model):
    pid = models.AutoField(primary_key=True)
    name = models.CharField(max_length = 36)
    goal = models.IntegerField()
    funded = models.IntegerField()
    startdate = models.DateTimeField()
    enddate = models.DateTimeField()
    
    def has_ended(self):
        return timezone.now() > self.enddate
    
    def is_funded(self):
        return self.funded >= self.goal
    
    def __str__(self):
        return "{}: {}".format(self.pid, self.name)
    
